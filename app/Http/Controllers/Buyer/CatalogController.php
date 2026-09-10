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

class CatalogController extends Controller
{
    public function home(): View
    {
        $categories = Category::where('is_active', true)->get();
        if ($categories->isEmpty()) {
            $categories = MarketData::categories();
        }

        $products = Product::with(['images', 'variants', 'category', 'seller.user', 'wishlists'])
            ->where('status', ProductStatus::ACTIVE)
            ->whereHas('seller', fn ($sq) => $sq->where('status', SellerStatus::VERIFIED))
            ->latest()
            ->take(8)
            ->get();

        if ($products->isEmpty()) {
            $products = MarketData::landingProducts();
        }

        $sellers = Seller::with(['user', 'products'])
            ->where('status', SellerStatus::VERIFIED)
            ->latest('id')
            ->take(8)
            ->get();

        if ($sellers->isEmpty()) {
            $sellers = MarketData::sellers();
        }

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
            $cats = Category::where('is_active', true)->select('id', 'name', 'slug')->get();

            return $cats->isEmpty() ? MarketData::categories() : $cats;
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
                'sellerAvatar' => $p->seller->user->avatar ?? '/assets/avatars/avatar-raisy.png',
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
            ->first();

        if ($productModel) {
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
                    'avatar' => $productModel->seller?->user?->avatar ?? '/assets/avatars/avatar-raisy.png',
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

                // Real data: no review system yet, so all zeros/empty
                'rating' => 0,
                'review_count' => 0,
                'sold_count' => 0,
                'rating_summary' => [
                    'rating' => 0,
                    'total_reviews' => 0,
                    'breakdown' => [
                        ['star' => 5, 'count' => 0, 'pct' => 0],
                        ['star' => 4, 'count' => 0, 'pct' => 0],
                        ['star' => 3, 'count' => 0, 'pct' => 0],
                        ['star' => 2, 'count' => 0, 'pct' => 0],
                        ['star' => 1, 'count' => 0, 'pct' => 0],
                    ],
                ],
                'reviews' => [],
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

        // Fallback to MarketData detail
        $fallback = MarketData::productDetail();

        return view('product-detail', [
            'product' => $fallback,
            'activeTab' => 'belanja',
            'title' => $fallback['title'].' | WhiMarket',
        ]);
    }

    public function sellerDirectory(): View
    {
        $sellers = Seller::with(['user', 'products'])
            ->where('status', SellerStatus::VERIFIED)
            ->latest('id')
            ->paginate(12);

        $sellersList = $sellers->map(function ($s) {
            $isDemo = in_array(strtolower($s->username), ['rachelvennya', 'celloszx', 'raisa6690', 'fuji_an', 'windahbasudara', 'bramastavrl']);

            return [
                'id' => $s->id,
                'name' => $s->store_name,
                'handle' => '@'.$s->username,
                'role' => 'Verified Creator',
                'category' => 'selebgram',
                'verified' => $s->isVerified(),
                'avatar' => $s->avatar_url,
                'cardBg' => $s->banner_url,
                'rating' => $isDemo ? 4.9 : 0.0,
                'itemCount' => $s->products->count(),
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
            ->first();

        if (! $seller) {
            // Fallback for demo usernames
            $seller = Seller::with(['user'])->firstOrFail();
        }

        $isOwnStore = Auth::check() && Auth::id() === $seller->user_id;
        $isFollowing = Auth::check() ? Auth::user()->isFollowing($seller) : false;
        $isDemo = in_array(strtolower($seller->username), ['rachelvennya', 'celloszx', 'raisa6690', 'fuji_an', 'windahbasudara', 'bramastavrl']);

        $stats = [
            'rating' => $isDemo ? 4.9 : null,
            'review_count' => $isDemo ? '1.2rb' : 0,
            'follower_count' => $seller->followers_count_formatted,
            'joined_date' => $seller->created_at ? $seller->created_at->translatedFormat('M Y') : 'Mar 2024',
        ];

        $reviewsList = $isDemo ? MarketData::sellerReviews() : [];

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
