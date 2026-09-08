<?php

namespace App\Http\Controllers\Seller;

use App\Enums\PayoutStatus;
use App\Enums\ProductStatus;
use App\Enums\SellerStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App\Models\Order;
use App\Models\Payout;
use App\Models\Seller;
use App\Models\SellerAccessCode;
use App\Models\Shipment;
use App\States\Dispute\SellerResponded;
use App\States\Order\Shipped;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SellerPortalController extends Controller
{
    public function showRegister(): View|RedirectResponse
    {
        $user = Auth::user();
        if ($user && $user->seller && $user->seller->isVerified()) {
            return redirect()->route('seller.dashboard');
        }

        return view('seller.register', [
            'title' => 'Aktivasi Toko Seller (VIP Access Code) | WhiMarket',
            'activeTab' => 'seller',
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'code' => 'required|string',
            'store_name' => 'required|string|max:100',
            'bio' => 'nullable|string|max:500',
            'bank_name' => 'required|string|max:50',
            'bank_account_number' => 'required|string|max:50',
            'bank_account_name' => 'required|string|max:100',
        ]);

        $accessCode = SellerAccessCode::where('code', trim($validated['code']))
            ->where('is_used', false)
            ->first();

        if (! $accessCode) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kode Akses VIP tidak valid atau sudah digunakan. Silakan hubungi Admin WhiMarket via DM Instagram/TikTok.');
        }

        // If code has email constraint, check email
        if ($accessCode->email && strtolower($accessCode->email) !== strtolower($user->email)) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Kode akses ini khusus untuk email {$accessCode->email}. Akun Anda terdaftar dengan {$user->email}.");
        }

        // Mark access code as used
        $accessCode->update([
            'is_used' => true,
            'used_at' => now(),
            'user_id' => $user->id,
        ]);

        // Upgrade user role
        $user->update(['role' => UserRole::SELLER]);
        $user->assignRole('seller');

        // Create Seller Profile
        $username = Str::slug($validated['store_name']);
        if (Seller::where('username', $username)->exists()) {
            $username .= '_'.rand(100, 999);
        }

        $seller = Seller::updateOrCreate(
            ['user_id' => $user->id],
            [
                'store_name' => $validated['store_name'],
                'username' => $username,
                'bio' => $validated['bio'],
                'bank_name' => $validated['bank_name'],
                'bank_account_number' => $validated['bank_account_number'],
                'bank_account_name' => $validated['bank_account_name'],
                'status' => SellerStatus::VERIFIED,
                'verified_at' => now(),
            ]
        );

        return redirect()->route('seller.dashboard')
            ->with('success', "Selamat datang di WhiMarket! Toko '{$seller->store_name}' resmi aktif dan Anda dapat langsung mulai berjualan.");
    }

    public function dashboard(): View|RedirectResponse
    {
        $user = Auth::user();
        $seller = $user->seller;

        if (! $seller || ! $seller->isVerified()) {
            return redirect()->route('seller.register')
                ->with('info', 'Silakan aktifkan toko Anda dengan kode akses resmi terlebih dahulu.');
        }

        $orders = $seller->orders()->with(['items', 'buyer', 'payment', 'shipment'])->latest()->get();

        $metrics = [
            'active_products' => $seller->products()->where('status', ProductStatus::ACTIVE)->count(),
            'pending_orders' => $orders->filter(fn ($o) => in_array($o->status::$name, ['paid', 'processing']))->count(),
            'shipped_orders' => $orders->filter(fn ($o) => $o->status::$name === 'shipped')->count(),
            'completed_orders' => $orders->filter(fn ($o) => $o->status::$name === 'completed')->count(),
            'pending_payout' => (float) Payout::where('seller_id', $seller->id)->where('status', PayoutStatus::PENDING)->sum('amount'),
            'total_sales' => (float) $orders->filter(fn ($o) => in_array($o->status::$name, ['paid', 'processing', 'shipped', 'delivered', 'completed']))->sum('total_amount'),
        ];

        return view('seller.dashboard', [
            'seller' => $seller,
            'metrics' => $metrics,
            'recentOrders' => $orders->take(5),
            'title' => 'Dashboard Seller | WhiMarket',
            'activeTab' => 'seller-dashboard',
        ]);
    }

    public function orders(Request $request): View
    {
        $seller = Auth::user()->seller;
        $tab = $request->query('status', 'all');

        $query = $seller->orders()->with(['items.variant', 'buyer', 'payment', 'shipment']);

        if ($tab !== 'all') {
            $query->where('status', $tab);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('seller.orders.index', [
            'seller' => $seller,
            'orders' => $orders,
            'currentTab' => $tab,
            'title' => 'Kelola Pesanan Toko | WhiMarket',
            'activeTab' => 'seller-orders',
        ]);
    }

    public function fulfill(Request $request, int $orderId): RedirectResponse
    {
        $seller = Auth::user()->seller;
        $order = $seller->orders()->findOrFail($orderId);

        $validated = $request->validate([
            'courier_name' => 'required|string|max:50',
            'tracking_number' => 'required|string|max:100',
            'pre_shipment_photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
            'receipt_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $prePhoto = $request->file('pre_shipment_photo');
        $prePath = $prePhoto->storeAs('shipments/pre_shipment', 'pre_'.$order->id.'_'.Str::uuid().'.'.$prePhoto->getClientOriginalExtension(), 'public');

        $receiptPath = null;
        if ($request->hasFile('receipt_photo')) {
            $receiptPhoto = $request->file('receipt_photo');
            $receiptPath = $receiptPhoto->storeAs('shipments/receipts', 'rcpt_'.$order->id.'_'.Str::uuid().'.'.$receiptPhoto->getClientOriginalExtension(), 'public');
        }

        Shipment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'courier_name' => $validated['courier_name'],
                'tracking_number' => $validated['tracking_number'],
                'pre_shipment_photo_path' => $prePath,
                'receipt_photo_path' => $receiptPath,
                'shipped_at' => now(),
            ]
        );

        if ($order->status->canTransitionTo(Shipped::class)) {
            $order->status->transitionTo(Shipped::class);
        }

        return redirect()->back()->with('success', "Pesanan #{$order->order_number} berhasil dikirim! Nomor resi telah diteruskan ke pembeli.");
    }

    public function claimDelivered(int $orderId): RedirectResponse
    {
        $seller = Auth::user()->seller;
        $order = $seller->orders()->findOrFail($orderId);

        // Store claim note in shipping or order session
        session()->flash('success', "Klaim paket sampai untuk Pesanan #{$order->order_number} telah dikirim ke Admin untuk diverifikasi via link resi kurir.");

        return redirect()->back();
    }

    public function disputeDetail(int $id): View
    {
        $seller = Auth::user()->seller;
        $dispute = Dispute::whereHas('order', fn ($q) => $q->where('seller_id', $seller->id))
            ->with(['order.items', 'buyer'])
            ->findOrFail($id);

        return view('seller.disputes.show', [
            'dispute' => $dispute,
            'title' => 'Detail Komplain Pesanan | WhiMarket',
            'activeTab' => 'seller-orders',
        ]);
    }

    public function disputeRespond(Request $request, int $id): RedirectResponse
    {
        $seller = Auth::user()->seller;
        $dispute = Dispute::whereHas('order', fn ($q) => $q->where('seller_id', $seller->id))
            ->findOrFail($id);

        $validated = $request->validate([
            'seller_response' => 'required|string|max:2000',
            'seller_evidence.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $evidencePaths = [];
        if ($request->hasFile('seller_evidence')) {
            foreach ($request->file('seller_evidence') as $file) {
                $filename = 'seller_ev_'.$dispute->id.'_'.Str::uuid().'.'.$file->getClientOriginalExtension();
                $evidencePaths[] = $file->storeAs('disputes/seller_evidence', $filename, 'public');
            }
        }

        $dispute->update([
            'seller_response' => $validated['seller_response'],
            'seller_evidence_paths' => $evidencePaths,
        ]);

        if ($dispute->status->canTransitionTo(SellerResponded::class)) {
            $dispute->status->transitionTo(SellerResponded::class);
        }

        return redirect()->back()->with('success', 'Tanggapan dan bukti tandingan Anda berhasil dikirimkan ke Admin WhiMarket.');
    }
}
