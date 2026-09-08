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
            ->latest()
            ->take(8)
            ->get();

        if ($products->isEmpty()) {
            $products = MarketData::landingProducts();
        }

        $sellers = Seller::with(['user', 'products'])
            ->where('status', SellerStatus::VERIFIED)
            ->take(6)
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

        $categories = Category::where('is_active', true)->get();

        $query = Product::with(['images', 'variants', 'category', 'seller.user', 'wishlists'])
            ->where('status', ProductStatus::ACTIVE);

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
        $products = $query->paginate(12)->withQueryString();

        $productsList = $products->map(function ($p) {
            return [
                'id' => $p->slug,
                'title' => $p->name,
                'sellerName' => $p->seller->store_name ?? 'WhiMarket Creator',
                'sellerAvatar' => $p->seller->user->avatar ?? '/assets/avatars/avatar-raisy.png',
                'verified' => $p->seller->isVerified() ?? true,
                'priceText' => 'Rp '.number_format((float) $p->price, 0, ',', '.'),
                'priceNumber' => (float) $p->price,
                'likes' => $p->wishlists_count ?? 10,
                'image' => $p->primary_image_url,
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
            foreach ($variants as $v) {
                $variantsMap[$v->name] = $v->id;
                if (str_contains($v->name, ' - ')) {
                    $parts = explode(' - ', $v->name);
                    $sz = trim(end($parts));
                    $variantsMap[$sz] = $v->id;
                }
            }
            $defaultDetail = MarketData::productDetail();
            $productData = array_merge($defaultDetail, [
                'id' => $productModel->slug,
                'model_id' => $productModel->id,
                'title' => $productModel->name,
                'category' => $productModel->category?->name ?? 'Merchandise',
                'category_slug' => $productModel->category?->slug ?? 'merchandise',
                'subcategory' => $productModel->category?->name ?? 'Barang',
                'badge' => $productModel->condition?->label() ?? 'Original Pre-loved',
                'price' => (float) $productModel->price,
                'price_formatted' => 'Rp '.number_format((float) $productModel->price, 0, ',', '.'),
                'description' => $productModel->description,
                'stock' => $productModel->total_stock ?: 10,
                'default_size' => $sizes[0] ?? 'All Size',
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
                'first_variant_id' => $variants->first()?->id,
            ]);

            return view('product-detail', [
                'product' => $productData,
                'productModel' => $productModel,
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
            ->paginate(12);

        return view('browse-seller', [
            'sellers' => $sellers,
            'activeTab' => 'seller',
            'title' => 'Daftar Seller Terverifikasi | WhiMarket',
        ]);
    }

    public function sellerProfile(string $username): View
    {
        $cleanUsername = ltrim($username, '@');

        $seller = Seller::with(['user', 'products.images', 'products.variants', 'products.category', 'products.wishlists'])
            ->where('username', $cleanUsername)
            ->first();

        if (! $seller) {
            // Fallback for demo usernames
            $seller = Seller::with(['user', 'products.images', 'products.variants', 'products.category'])->firstOrFail();
        }

        return view('seller-profile', [
            'seller' => $seller,
            'products' => $seller->products,
            'activeTab' => 'seller',
            'title' => $seller->store_name.' (@'.$seller->username.') | WhiMarket',
        ]);
    }
}
