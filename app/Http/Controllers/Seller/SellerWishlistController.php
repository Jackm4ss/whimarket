<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SellerWishlistController extends Controller
{
    private function getSeller(): ?Seller
    {
        $user = Auth::user();
        if ($user && $user->isAdmin()) {
            return null;
        }

        return $user?->seller;
    }

    /**
     * Display wishlist insights and activities for this seller's products.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = Auth::user();
        if ($user && $user->isAdmin()) {
            return redirect('/admin/products')->with('info', 'Kelola data produk di Admin Panel.');
        }

        $seller = $this->getSeller();
        if (! $seller) {
            return redirect()->route('seller.register')
                ->with('info', 'Silakan aktifkan toko Anda dengan kode akses resmi terlebih dahulu.');
        }

        $currentTab = (string) $request->query('tab', 'activity');
        $search = trim((string) $request->query('q', ''));
        $productId = $request->query('product_id');

        // 1. Overall Metrics (Cached / Aggregated efficiently)
        $totalWishlists = $seller->productWishlists()->count();
        $uniqueWishlisters = $seller->productWishlists()->distinct('wishlists.user_id')->count('wishlists.user_id');
        $wishlistedProductsCount = $seller->products()->has('wishlists')->count();

        // 2. Tab Data
        if ($currentTab === 'products') {
            // Grouped view: products ranked by wishlist count
            $productsQuery = $seller->products()
                ->withCount('wishlists')
                ->having('wishlists_count', '>', 0)
                ->with([
                    'wishlists' => fn ($wq) => $wq->with('user')->latest('created_at'),
                    'category',
                    'variants',
                ])
                ->orderByDesc('wishlists_count');

            if ($search !== '') {
                $productsQuery->where('name', 'like', "%{$search}%");
            }

            $products = $productsQuery->paginate(12)->withQueryString();
            $activities = null;
        } else {
            // Activity log view: chronological stream of wishlists with buyer & product
            $activityQuery = $seller->productWishlists()
                ->with([
                    'user',
                    'product.images',
                    'product.category',
                    'product.variants',
                ])
                ->latest('wishlists.created_at');

            if ($search !== '') {
                $activityQuery->where(function ($q) use ($search) {
                    $q->whereHas('product', fn ($pq) => $pq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$search}%"));
                });
            }

            if (! empty($productId)) {
                $activityQuery->where('wishlists.product_id', (int) $productId);
            }

            $activities = $activityQuery->paginate(15)->withQueryString();
            $products = null;
        }

        // List of seller products for filtering
        $filterProducts = $seller->products()
            ->has('wishlists')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('seller.wishlists.index', [
            'seller' => $seller,
            'currentTab' => $currentTab,
            'search' => $search,
            'productId' => $productId,
            'totalWishlists' => $totalWishlists,
            'uniqueWishlisters' => $uniqueWishlisters,
            'wishlistedProductsCount' => $wishlistedProductsCount,
            'activities' => $activities,
            'products' => $products,
            'filterProducts' => $filterProducts,
            'title' => 'Peminat Wishlist Produk | WhiMarket Seller Portal',
            'activeTab' => 'seller-wishlists',
        ]);
    }
}
