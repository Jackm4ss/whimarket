<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SellerFollowerController extends Controller
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
     * Display a list of buyers following this seller's store.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = Auth::user();
        if ($user && $user->isAdmin()) {
            return redirect('/admin/sellers')->with('info', 'Kelola data seller di Admin Panel.');
        }

        $seller = $this->getSeller();
        if (! $seller) {
            return redirect()->route('seller.register')
                ->with('info', 'Silakan aktifkan toko Anda dengan kode akses resmi terlebih dahulu.');
        }

        $search = trim((string) $request->query('q', ''));
        $loyaltyFilter = (string) $request->query('filter', 'all');

        $query = $seller->sellerFollowers()
            ->with(['user'])
            ->latest('created_at');

        if ($search !== '') {
            $query->whereHas('user', function ($uq) use ($search) {
                $uq->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $followers = $query->paginate(15)->withQueryString();

        // High-performance batch order aggregation to prevent N+1 queries
        $followerUserIds = $followers->pluck('user_id')->filter()->all();
        $orderCounts = [];
        if (! empty($followerUserIds)) {
            $orderCounts = Order::where('seller_id', $seller->id)
                ->whereIn('buyer_id', $followerUserIds)
                ->select('buyer_id', DB::raw('count(*) as order_count'))
                ->groupBy('buyer_id')
                ->pluck('order_count', 'buyer_id')
                ->toArray();
        }

        $totalFollowers = $seller->followers()->count();

        return view('seller.followers.index', [
            'seller' => $seller,
            'followers' => $followers,
            'orderCounts' => $orderCounts,
            'totalFollowers' => $totalFollowers,
            'search' => $search,
            'loyaltyFilter' => $loyaltyFilter,
            'title' => 'Pengikut Toko | WhiMarket Seller Portal',
            'activeTab' => 'seller-followers',
        ]);
    }
}
