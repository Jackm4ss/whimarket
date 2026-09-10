<?php

namespace App\Http\Controllers\Buyer;

use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\SellerFollower;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FollowController extends Controller
{
    /**
     * Display a list of stores followed by the authenticated user.
     */
    public function index(): View
    {
        $user = Auth::user();

        $followedSellers = $user ? $user->followedSellers()
            ->with([
                'user',
                'products' => fn ($q) => $q->where('status', ProductStatus::ACTIVE)->with('images')->latest('id')->limit(4),
            ])
            ->withCount(['followers', 'products'])
            ->latest('seller_followers.created_at')
            ->get() : collect();

        return view('followed-sellers', [
            'followedSellers' => $followedSellers,
            'title' => 'Toko yang Diikuti | WhiMarket',
            'activeTab' => 'followed_sellers',
        ]);
    }

    /**
     * Toggle follow/unfollow for a given seller.
     */
    public function toggle(Request $request, string|int $sellerId): JsonResponse|RedirectResponse
    {
        if (! Auth::check()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'error' => 'Unauthenticated',
                    'message' => 'Silakan masuk ke akun Anda terlebih dahulu untuk mengikuti toko.',
                    'redirect' => route('login'),
                ], 401);
            }

            return redirect()->guest(route('login'))->with('error', 'Silakan login terlebih dahulu untuk mengikuti toko.');
        }

        $user = Auth::user();
        $cleanId = is_string($sellerId) ? ltrim($sellerId, '@') : $sellerId;

        $seller = Seller::where('id', $cleanId)
            ->orWhere('username', $cleanId)
            ->firstOrFail();

        // Prevent seller from following their own store
        if ((int) $user->id === (int) $seller->user_id) {
            $msg = 'Anda tidak dapat mengikuti toko Anda sendiri.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'error' => 'SelfFollowForbidden',
                    'message' => $msg,
                ], 422);
            }

            return back()->with('error', $msg);
        }

        $existing = SellerFollower::where('user_id', $user->id)
            ->where('seller_id', $seller->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $isFollowing = false;
            $message = "Berhenti mengikuti {$seller->store_name}.";
        } else {
            SellerFollower::create([
                'user_id' => $user->id,
                'seller_id' => $seller->id,
            ]);
            $isFollowing = true;
            $message = "Berhasil mengikuti {$seller->store_name}!";
        }

        $followersCount = $seller->followers()->count();
        $formattedFollowersCount = $seller->followers_count_formatted;
        $userFollowedCount = $user->followedSellers()->count();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_following' => $isFollowing,
                'followers_count' => $followersCount,
                'formatted_followers_count' => $formattedFollowersCount,
                'user_followed_count' => $userFollowedCount,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
