<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $wishlists = $user ? $user->wishlists()
            ->with([
                'product' => fn ($q) => $q->withCount('wishlists'),
                'product.images',
                'product.variants',
                'product.category',
                'product.seller.user',
                'product.wishlists',
            ])
            ->latest()
            ->get() : collect();

        return view('wishlist', [
            'wishlists' => $wishlists,
            'title' => 'Wishlist Saya | WhiMarket',
            'activeTab' => 'wishlist',
        ]);
    }

    public function toggle(string|int $productId): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json([
                'error' => 'Unauthenticated',
                'redirect' => route('login'),
            ], 401);
        }

        $user = Auth::user();
        $product = Product::where('id', $productId)->orWhere('slug', $productId)->firstOrFail();

        $existing = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $isLiked = false;
            $message = 'Dihapus dari wishlist.';
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            $isLiked = true;
            $message = 'Ditambahkan ke wishlist!';
        }

        $likesCount = Wishlist::where('product_id', $product->id)->count();
        $userWishlistsCount = Wishlist::where('user_id', $user->id)->count();

        return response()->json([
            'success' => true,
            'is_liked' => $isLiked,
            'likes_count' => $likesCount,
            'user_wishlists_count' => $userWishlistsCount,
            'message' => $message,
        ]);
    }
}
