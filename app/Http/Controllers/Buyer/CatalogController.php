<?php

namespace App\Http\Controllers\Buyer;

use App\Enums\ProductStatus;
use App\Enums\SellerStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use App\Support\MarketData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Spatie\ModelStates\State;

class CatalogController extends Controller
{
    public function home(): View
    {
        $categories = Category::where('is_active', true)->get();

        $products = Product::with(['images', 'variants', 'category', 'seller.user', 'wishlists'])
            ->where('status', ProductStatus::ACTIVE)
            ->whereHas('seller', fn ($sq) => $sq->where('status', SellerStatus::VERIFIED))
            ->latest()
            ->take(8)
            ->get();

        $sellers = Seller::with(['user', 'products'])
            ->where('status', SellerStatus::VERIFIED)
            ->latest('id')
            ->take(8)
            ->get();

        return view('landing', [
            'categories' => $categories,
            'products' => $products,
            'sellers' => $sellers,
            'steps' => MarketData::steps(),
            'activeTab' => 'beranda',
        ]);
    }

    public function shop(Request $request): View
    {
        $categorySlug = $request->query('kategori', 'all');
        $conditionParam = $request->query('kondisi');
        $minPrice = $request->query('min_harga');
        $maxPrice = $request->query('max_harga');
        $sort = $request->query('sort', 'terbaru');
        $search = $request->query('q');

        $categories = Cache::remember('shop_active_categories', 300, function () {
            return Category::where('is_active', true)->select('id', 'name', 'slug')->get();
        });

        $query = Product::query()
            ->select(['id', 'seller_id', 'category_id', 'name', 'slug', 'description', 'price', 'condition', 'status', 'created_at'])
            ->with([
                'category:id,name,slug',
                'primaryImage:id,product_id,image_path',
                'seller:id,user_id,store_name,status,verified_at',
                'seller.user:id,name,avatar',
                'variants:id,product_id,name,price,stock',
            ])
            ->withCount('wishlists')
            ->where('status', ProductStatus::ACTIVE)
            ->whereHas('seller', fn ($sq) => $sq->where('status', SellerStatus::VERIFIED));
        if ($categorySlug && $categorySlug !== 'all') {
            $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        if ($conditionParam) {
            $query->where('condition', $conditionParam);
        }

        if ($minPrice) {
            $query->where('price', '>=', (float) $minPrice);
        }

        if ($maxPrice) {
            $query->where('price', '<=', (float) $maxPrice);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('seller', fn ($sq) => $sq->where('store_name', 'like', "%{$search}%"));
            });
        }

        match ($sort) {
            'harga-rendah' => $query->orderBy('price', 'asc'),
            'harga-tinggi' => $query->orderBy('price', 'desc'),
            'terpopuler' => $query->orderByDesc('wishlists_count'),
            default => $query->latest('id'),
        };

        $products = $query->paginate(12)->withQueryString();
        $userWishlistIds = Auth::check() ? Auth::user()->wishlists()->pluck('product_id')->toArray() : [];
        $productsList = $products->map(function ($p) use ($userWishlistIds) {
            return [
                'id' => $p->slug,
                'model_id' => $p->id,
                'title' => $p->name,
                'sellerName' => $p->seller->store_name ?? 'WhiMarket Creator',
                'sellerAvatar' => $p->seller?->avatar_url,
                'verified' => $p->seller->isVerified() ?? true,
                'is_liked' => in_array($p->id, $userWishlistIds),
                'priceText' => 'Rp '.number_format((float) $p->price, 0, ',', '.'),
                'priceNumber' => (float) $p->price,
                'likes' => $p->wishlists_count ?? 10,
                'image' => $p->primary_image_url,
                'stock' => (int) $p->total_stock,
                'is_out_of_stock' => $p->total_stock <= 0,
                'condition' => $p->condition?->label() ?? 'Like New',
                'category' => $p->category?->slug ?? 'fashion',
                'href' => route('product.detail', $p->slug),
            ];
        })->values()->all();

        return view('shop', [
            'products' => $products,
            'productsList' => $productsList,
            'categories' => $categories,
            'initialCategory' => $categorySlug,
            'activeTab' => 'belanja',
        ]);
    }

    public function productDetail(string $slug): View
    {
        $productModel = Product::with(['images', 'variants', 'category', 'seller.user', 'wishlists'])
            ->where('slug', $slug)
            ->orWhere('id', $slug)
            ->firstOrFail();
        // Build view data array matching product-detail.blade.php contract
        $gallery = [];
        foreach ($productModel->images as $img) {
            $gallery[] = [
                'id' => (string) $img->id,
                'thumb' => $img->image_path,
                'main' => $img->image_path,
                'alt' => $productModel->name,
            ];
        }
        if (empty($gallery)) {
            $gallery[] = [
                'id' => '1',
                'thumb' => $productModel->primary_image_url,
                'main' => $productModel->primary_image_url,
                'alt' => $productModel->name,
            ];
        }

        $variants = $productModel->variants;
        $rawSizes = $variants->pluck('name')->map(function ($name) {
            if (str_contains($name, ' - ')) {
                $parts = explode(' - ', $name);

                return trim(end($parts));
            }

            return $name;
        })->unique()->values()->all();
        $sizes = ! empty($rawSizes) ? $rawSizes : ['All Size'];

        $variantsMap = [];
        $variantsStockMap = [];
        foreach ($variants as $v) {
            $variantsMap[$v->name] = $v->id;
            $variantsStockMap[$v->id] = (int) $v->stock;
            if (str_contains($v->name, ' - ')) {
                $parts = explode(' - ', $v->name);
                $sz = trim(end($parts));
                $variantsMap[$sz] = $v->id;
                $variantsStockMap[$sz] = (int) $v->stock;
            } else {
                $variantsStockMap[$v->name] = (int) $v->stock;
            }
        }
        $catSlug = $productModel->category?->slug ?? '';
        $prodName = strtolower($productModel->name);

        $isFashion = (in_array($catSlug, ['fashion', 'merchandise']) && ! str_contains($prodName, 'topi')) || str_contains($prodName, 'hoodie') || str_contains($prodName, 't-shirt') || str_contains($prodName, 'jaket');
        $isPerfume = $catSlug === 'kecantikan' || str_contains($prodName, 'parfum');

        $variantLabel = match (true) {
            $isPerfume => 'Pilih Ukuran / Volume',
            $isFashion => 'Pilih Ukuran',
            $catSlug === 'elektronik' => 'Pilih Varian',
            $catSlug === 'hobi' => 'Pilih Edisi / Varian',
            default => 'Pilih Varian',
        };

        $colors = [];
        $hasColors = false;
        if ($productModel->slug === 'prod-hoodie-dream-plan-do' || str_contains($productModel->slug, 'hoodie-dream-plan-do')) {
            $hasColors = true;
            $colors = [
                ['id' => 'purple', 'name' => 'Purple', 'image' => '/assets/products/prod-hoodie.png', 'active' => true],
                ['id' => 'black', 'name' => 'Black', 'image' => '/assets/detail/hoodie_color_black.png', 'active' => false],
                ['id' => 'white', 'name' => 'White', 'image' => '/assets/detail/hoodie_color_white.png', 'active' => false],
                ['id' => 'grey', 'name' => 'Grey', 'image' => '/assets/detail/hoodie_color_grey.png', 'active' => false],
            ];
        }

        $defaultDetail = MarketData::productDetail();
        $realReviews = $productModel->reviews()
            ->with(['user', 'orderItem'])
            ->latest()
            ->get();

        $realReviewsCount = $realReviews->count();
        $isDemoHoodie = ($productModel->slug === 'prod-hoodie-dream-plan-do' || str_contains($productModel->slug, 'hoodie-dream-plan-do'));

        if ($realReviewsCount > 0) {
            $avgRating = round($realReviews->avg('rating'), 1);
            $breakdown = [];
            for ($star = 5; $star >= 1; $star--) {
                $count = $realReviews->where('rating', $star)->count();
                $pct = (int) round(($count / $realReviewsCount) * 100);
                $breakdown[] = [
                    'star' => $star,
                    'count' => $count,
                    'pct' => $pct,
                ];
            }
            $formattedReviews = $realReviews->map(function ($rev) {
                $user = $rev->user;
                $authorName = $user?->name ?: 'Pembeli';
                $userAvatar = $user?->avatar_url ?: '/assets/avatars/avatar-default.png';

                return [
                    'id' => $rev->id,
                    'author' => $authorName,
                    'user_name' => $authorName,
                    'avatar' => $userAvatar,
                    'user_avatar' => $userAvatar,
                    'rating' => (int) $rev->rating,
                    'date' => $rev->created_at->diffForHumans(),
                    'comment' => $rev->comment,
                    'photos' => $rev->photos ?? [],
                    'images' => $rev->photos ?? [],
                    'video' => $rev->video,
                    'likes' => 0,
                ];
            })->all();

            $rating = $avgRating;
            $reviewCount = $realReviewsCount;
            $ratingSummary = [
                'rating' => $avgRating,
                'total_reviews' => $realReviewsCount,
                'breakdown' => $breakdown,
            ];
            $reviewsList = $formattedReviews;
        } elseif ($isDemoHoodie) {
            $rating = 4.8;
            $reviewCount = 620;
            $ratingSummary = MarketData::productDetail()['rating_summary'];
            $reviewsList = MarketData::productDetail()['reviews'];
        } else {
            $rating = 0.0;
            $reviewCount = 0;
            $ratingSummary = [
                'rating' => 0.0,
                'total_reviews' => 0,
                'breakdown' => [
                    ['star' => 5, 'count' => 0, 'pct' => 0],
                    ['star' => 4, 'count' => 0, 'pct' => 0],
                    ['star' => 3, 'count' => 0, 'pct' => 0],
                    ['star' => 2, 'count' => 0, 'pct' => 0],
                    ['star' => 1, 'count' => 0, 'pct' => 0],
                ],
            ];
            $reviewsList = [];
        }

        $realSoldCount = (int) $productModel->variants()->with('orderItems.order')->get()
            ->flatMap->orderItems
            ->filter(function ($item) {
                $order = $item->order;
                if (! $order) {
                    return false;
                }
                $status = $order->status instanceof State ? $order->status::$name : (string) $order->status;

                return in_array($status, ['paid', 'processing', 'shipped', 'delivered', 'completed'], true);
            })
            ->sum('quantity');
        $soldCount = ($realSoldCount === 0 && $isDemoHoodie) ? '900+' : (string) $realSoldCount;

        $productData = array_merge($defaultDetail, [
            'id' => $productModel->slug,
            'model_id' => $productModel->id,
            'title' => $productModel->name,
            'category' => $productModel->category?->name ?? 'Merchandise',
            'category_slug' => $productModel->category?->slug ?? 'merchandise',
            'subcategory' => $productModel->category?->name ?? 'Barang',
            'badge' => $productModel->condition?->label() ?? 'Seperti Baru',
            'price' => (float) $productModel->price,
            'price_formatted' => 'Rp '.number_format((float) $productModel->price, 0, ',', '.'),
            'description' => $productModel->description,
            'stock' => (int) $productModel->total_stock,
            'is_out_of_stock' => $productModel->total_stock <= 0,
            'default_size' => $sizes[0] ?? 'All Size',
            'has_colors' => $hasColors,
            'colors' => $colors,
            'variant_label' => $variantLabel,
            'seller' => [
                'name' => $productModel->seller?->store_name ?? 'WhiMarket Creator',
                'username' => $productModel->seller?->username ?? 'creator',
                'role' => 'Verified Creator',
                'avatar' => $productModel->seller?->avatar_url,
                'verified' => $productModel->seller?->isVerified() ?? true,
                'href' => '/seller/@'.($productModel->seller?->username ?? 'creator'),
            ],
            'breadcrumbs' => [
                ['name' => 'Beranda', 'href' => '/'],
                ['name' => $productModel->category?->name ?? 'Belanja', 'href' => '/belanja?kategori='.($productModel->category?->slug ?? 'all')],
                ['name' => $productModel->name, 'href' => null],
            ],
            'gallery' => $gallery,
            'sizes' => $sizes,
            'variants_map' => $variantsMap,
            'variants_stock_map' => $variantsStockMap,
            'first_variant_id' => $variants->first()?->id,
            'first_variant_stock' => (int) ($variants->first()?->stock ?? 0),

            // Real reviews and rating calculation
            'rating' => $rating,
            'review_count' => $reviewCount,
            'sold_count' => $soldCount,
            'rating_summary' => $ratingSummary,
            'reviews' => $reviewsList,
        ]);

        $isOwnProduct = Auth::check() && $productModel->seller && Auth::id() === $productModel->seller->user_id;
        $isWishlisted = Auth::check() ? $productModel->wishlists()->where('user_id', Auth::id())->exists() : false;
        $isFollowingSeller = Auth::check() && $productModel->seller ? Auth::user()->isFollowing($productModel->seller) : false;
        $isSellerActive = $productModel->seller && $productModel->seller->status === SellerStatus::VERIFIED;
        $isActive = ($productModel->status === ProductStatus::ACTIVE) && $isSellerActive;

        return view('product-detail', [
            'product' => $productData,
            'productModel' => $productModel,
            'isOwnProduct' => $isOwnProduct,
            'isWishlisted' => $isWishlisted,
            'isActive' => $isActive,
            'isFollowingSeller' => $isFollowingSeller,
            'isSellerActive' => $isSellerActive,
            'activeTab' => 'belanja',
            'title' => $productModel->name.' | WhiMarket',
        ]);
    }

    public function sellerDirectory(): View
    {
        $sellers = Seller::with(['user', 'products'])
            ->withCount(['reviews', 'products'])
            ->withAvg('reviews', 'rating')
            ->where('status', SellerStatus::VERIFIED)
            ->latest('id')
            ->paginate(12);

        $sellersList = $sellers->map(function ($s) {
            $isDemo = in_array(strtolower($s->username), ['rachelvennya', 'celloszx', 'raisa6690', 'fuji_an', 'windahbasudara', 'bramastavrl']);
            $hasReviews = ($s->reviews_count ?? 0) > 0;
            $rating = $hasReviews ? round((float) ($s->reviews_avg_rating ?? 0), 1) : ($isDemo ? 4.9 : 0.0);
            $reviewCount = $hasReviews ? $s->reviews_count : ($isDemo ? '1.2rb' : 0);

            return [
                'id' => $s->id,
                'name' => $s->store_name,
                'username' => $s->username,
                'handle' => '@'.$s->username,
                'role' => 'Verified Creator',
                'category' => 'selebgram',
                'verified' => $s->isVerified(),
                'avatar' => $s->avatar_url,
                'cardBg' => $s->banner_url,
                'rating' => $rating,
                'reviewCount' => $reviewCount,
                'itemCount' => $s->products_count ?? $s->products->count(),
                'followerCount' => $s->followers_count_formatted,
                'profileUrl' => route('seller.profile', '@'.$s->username),
            ];
        })->values()->all();

        return view('browse-seller', [
            'sellers' => $sellers,
            'sellersList' => $sellersList,
            'activeTab' => 'seller',
            'title' => 'Daftar Seller Terverifikasi | WhiMarket',
        ]);
    }

    public function sellerProfile(string $username): View
    {
        $cleanUsername = ltrim($username, '@');

        $seller = Seller::with(['user'])
            ->where('username', $cleanUsername)
            ->firstOrFail();

        $isOwnStore = Auth::check() && Auth::id() === $seller->user_id;
        $isFollowing = Auth::check() ? Auth::user()->isFollowing($seller) : false;
        $isDemo = in_array(strtolower($seller->username), ['rachelvennya', 'celloszx', 'raisa6690', 'fuji_an', 'windahbasudara', 'bramastavrl']);

        $realSellerReviews = $seller->reviews()
            ->with(['user', 'product.images', 'product.primaryImage', 'orderItem'])
            ->latest()
            ->get();
        $realCount = $realSellerReviews->count();

        if ($realCount > 0) {
            $avgRating = round($realSellerReviews->avg('rating'), 1);
            $statsRating = $avgRating;
            $statsReviewCount = $realCount >= 1000 ? number_format($realCount / 1000, 1, ',', '.').'rb' : (string) $realCount;
            $reviewsList = $realSellerReviews->map(function ($rev) {
                $user = $rev->user;
                $authorName = $user?->name ?: 'Pembeli';
                $userAvatar = $user?->avatar_url ?: '/assets/avatars/avatar-default.png';

                $product = $rev->product;
                $productTitle = $product?->name ?? ($rev->orderItem?->product_name_snapshot ?? 'Produk');
                $productImage = $product?->primary_image_url ?: '/assets/products/prod-hoodie.png';
                $productUrl = $product?->slug ? route('product.detail', $product->slug) : '#';

                return [
                    'id' => $rev->id,
                    'author' => $authorName,
                    'user_name' => $authorName,
                    'avatar' => $userAvatar,
                    'user_avatar' => $userAvatar,
                    'rating' => (int) $rev->rating,
                    'date' => $rev->created_at->diffForHumans(),
                    'comment' => $rev->comment,
                    'photos' => $rev->photos ?? [],
                    'images' => $rev->photos ?? [],
                    'video' => $rev->video,
                    'verified' => true,
                    'product' => [
                        'title' => $productTitle,
                        'priceText' => 'Rp '.number_format((float) ($rev->orderItem?->subtotal ?? $rev->orderItem?->price_snapshot ?? 0), 0, ',', '.'),
                        'variant' => $rev->orderItem?->variant_name_snapshot ?? '',
                        'image' => $productImage,
                        'url' => $productUrl,
                    ],
                ];
            })->all();
        } elseif ($isDemo) {
            $statsRating = 4.9;
            $statsReviewCount = '1.2rb';
            $reviewsList = MarketData::sellerReviews();
        } else {
            $statsRating = null;
            $statsReviewCount = 0;
            $reviewsList = [];
        }

        $stats = [
            'rating' => $statsRating,
            'review_count' => $statsReviewCount,
            'follower_count' => $seller->followers_count_formatted,
            'joined_date' => $seller->created_at ? $seller->created_at->translatedFormat('M Y') : 'Mar 2024',
        ];

        $products = $seller->products()
            ->with(['images', 'variants', 'category', 'wishlists'])
            ->when(! $isOwnStore, fn ($q) => $q->where('status', ProductStatus::ACTIVE))
            ->latest('id')
            ->get();

        return view('seller-profile', [
            'seller' => $seller,
            'products' => $products,
            'isOwnStore' => $isOwnStore,
            'isFollowing' => $isFollowing,
            'isStoreActive' => $seller->status === SellerStatus::VERIFIED,
            'stats' => $stats,
            'reviewsList' => $reviewsList,
            'activeTab' => 'seller',
            'title' => $seller->store_name.' (@'.$seller->username.') | WhiMarket',
        ]);
    }
}
