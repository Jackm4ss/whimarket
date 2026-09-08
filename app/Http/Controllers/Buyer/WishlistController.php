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
        $wishlists = $user ? $user->wishlists()->with(['product.images', 'product.variants', 'product.category', 'product.seller.user'])->latest()->get() : collect();

        return view('wishlist', [
            'wishlists' => $wishlists,
            'title' => 'Wishlist Saya | WhiMarket',
            'activeTab' => 'wishlist',
        ]);
    }

    public function toggle(int $productId): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json([
                'error' => 'Unauthenticated',
                'redirect' => route('auth.google.redirect'),
            ], 401);
        }

        $user = Auth::user();
        $product = Product::findOrFail($productId);

        $existing = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $existing->delete();
            $isLiked = false;
            $message = 'Dihapus dari wishlist.';
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $productId,
            ]);
            $isLiked = true;
            $message = 'Ditambahkan ke wishlist!';
        }

        $likesCount = Wishlist::where('product_id', $productId)->count();

        return response()->json([
            'success' => true,
            'is_liked' => $isLiked,
            'likes_count' => $likesCount,
            'message' => $message,
        ]);
    }
}
