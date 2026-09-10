<?php

namespace App\Http\Controllers\Buyer;

use App\Enums\PaymentStatus;
use App\Enums\PayoutStatus;
use App\Enums\ProductStatus;
use App\Enums\SellerStatus;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\EscrowBalance;
use App\Models\Order;
use App\Models\Payout;
use App\States\Order\Cancelled;
use App\States\Order\Completed;
use App\States\Order\Delivered;
use App\States\Order\PaymentVerification;
use App\States\Order\PendingPayment;
use App\States\Order\Shipped;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Spatie\ModelStates\State;

class OrderController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = Auth::user();
        if ($user && $user->isAdmin()) {
            return redirect('/admin/orders')->with('info', 'Kelola seluruh pesanan pelanggan di Admin Panel.');
        }
        $status = (string) $request->query('status', 'all');
        $search = trim((string) $request->query('search', ''));

        $query = $user->orders()
            ->with([
                'items.variant.product.images',
                'items.review',
                'seller.user',
                'payment',
                'shipment',
            ]);

        // Filter status tab
        if ($status === 'unpaid') {
            $query->where('status', 'pending_payment');
        } elseif ($status === 'processing') {
            $query->whereIn('status', ['payment_verification', 'paid', 'processing'])
                ->whereDoesntHave('payment', function ($pq) {
                    $pq->where('status', PaymentStatus::REJECTED);
                });
        } elseif ($status === 'shipped') {
            $query->where('status', 'shipped');
        } elseif ($status === 'delivered') {
            $query->where('status', 'delivered');
        } elseif ($status === 'completed') {
            $query->where('status', 'completed');
        } elseif ($status === 'cancelled') {
            $query->where(function ($q) {
                $q->whereIn('status', ['cancelled', 'disputed'])
                    ->orWhereHas('payment', function ($pq) {
                        $pq->where('status', PaymentStatus::REJECTED);
                    });
            });
        }

        // Filter search keyword
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('items', function ($iq) use ($search) {
                        $iq->where('product_name_snapshot', 'like', "%{$search}%");
                    })
                    ->orWhereHas('seller', function ($sq) use ($search) {
                        $sq->where('store_name', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        // Calculate count for each status tab
        $allUserOrders = $user->orders()->with('payment')->get();
        $counts = [
            'all' => $allUserOrders->count(),
            'unpaid' => 0,
            'processing' => 0,
            'shipped' => 0,
            'delivered' => 0,
            'completed' => 0,
            'cancelled' => 0,
        ];

        foreach ($allUserOrders as $uo) {
            $name = $uo->status instanceof State ? $uo->status::$name : (string) $uo->status;
            $isRejected = ($uo->payment?->status === PaymentStatus::REJECTED || ! empty($uo->payment?->rejection_reason));

            if ($name === 'pending_payment') {
                $counts['unpaid']++;
            } elseif ($isRejected || in_array($name, ['cancelled', 'disputed'], true)) {
                $counts['cancelled']++;
            } elseif (in_array($name, ['payment_verification', 'paid', 'processing'], true)) {
                $counts['processing']++;
            } elseif ($name === 'shipped') {
                $counts['shipped']++;
            } elseif ($name === 'delivered') {
                $counts['delivered']++;
            } elseif ($name === 'completed') {
                $counts['completed']++;
            }
        }

        return view('orders.index', [
            'orders' => $orders,
            'title' => 'Pesanan Saya | WhiMarket',
            'activeTab' => 'pesanan',
            'currentTab' => $status,
            'search' => $search,
            'counts' => $counts,
        ]);
    }

    public function show(string $orderNumber): View
    {
        $order = Order::with(['items.variant.product.images', 'items.review', 'seller.user', 'payment', 'shipment', 'dispute'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        Gate::authorize('view', $order);

        return view('orders.show', [
            'order' => $order,
            'title' => 'Detail Pesanan #'.$order->order_number.' | WhiMarket',
            'activeTab' => 'pesanan',
        ]);
    }

    public function confirmDelivered(string $orderNumber): RedirectResponse
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        Gate::authorize('view', $order);

        // Transition status to delivered if shipped
        if ($order->status instanceof Shipped || $order->status->equals(Shipped::class)) {
            $order->status->transitionTo(Delivered::class);
            $order->update([
                'inspection_deadline_at' => now()->addHours(48),
            ]);

            if ($order->shipment) {
                $order->shipment->update(['delivered_at' => now()]);
            }
        }

        return redirect()->back()->with('success', 'Konfirmasi berhasil! Masa pemeriksaan 48 jam dimulai.');
    }

    public function completeOrder(string $orderNumber): RedirectResponse
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        Gate::authorize('view', $order);

        if ($order->status instanceof Delivered || $order->status->equals(Delivered::class)) {
            $order->status->transitionTo(Completed::class);
            $order->update([
                'completed_at' => now(),
            ]);

            // Release escrow balance and create payout
            $escrow = EscrowBalance::where('order_id', $order->id)->first();
            if ($escrow) {
                $escrow->update([
                    'is_released' => true,
                    'released_at' => now(),
                ]);
            }

            Payout::firstOrCreate(
                ['order_id' => $order->id],
                [
                    'seller_id' => $order->seller_id,
                    'amount' => $order->total_amount,
                    'bank_details_snapshot' => [
                        'bank_name' => $order->seller->bank_name,
                        'account_number' => $order->seller->bank_account_number,
                        'account_name' => $order->seller->bank_account_name,
                    ],
                    'status' => PayoutStatus::PENDING,
                ]
            );
        }

        return redirect()->back()->with('success', 'Pesanan selesai! Terima kasih telah berbelanja di WhiMarket.');
    }

    public function cancel(string $orderNumber): RedirectResponse
    {
        $order = Order::with('items.variant')->where('order_number', $orderNumber)->firstOrFail();
        Gate::authorize('view', $order);

        if (! $order->status->equals(PendingPayment::class) && ! $order->status->equals(PaymentVerification::class)) {
            return redirect()->back()->with('error', 'Pesanan ini saat ini tidak dapat dibatalkan.');
        }

        if ($order->status->canTransitionTo(Cancelled::class)) {
            $order->status->transitionTo(Cancelled::class);
        }
        $order->restoreStock();

        return redirect()->route('orders.show', $order->order_number)
            ->with('success', 'Pesanan berhasil dibatalkan dan stok produk telah dikembalikan ke etalase.');
    }

    public function reorder(string $orderNumber): RedirectResponse
    {
        $order = Order::with(['items.variant.product.seller'])->where('order_number', $orderNumber)->firstOrFail();
        Gate::authorize('view', $order);

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $addedCount = 0;

        foreach ($order->items as $item) {
            $variant = $item->variant;
            if (! $variant || $variant->stock <= 0) {
                continue;
            }

            $isProductActive = $variant->product && $variant->product->status === ProductStatus::ACTIVE;
            $isSellerActive = $variant->product?->seller && $variant->product->seller->status === SellerStatus::VERIFIED;
            if (! $isProductActive || ! $isSellerActive) {
                continue;
            }

            $qtyToAdd = min($item->quantity, $variant->stock);
            $cartItem = $cart->items()->where('product_variant_id', $variant->id)->first();
            if ($cartItem) {
                $newQty = min($cartItem->quantity + $qtyToAdd, $variant->stock);
                $cartItem->update(['quantity' => $newQty, 'is_selected' => true]);
            } else {
                $cart->items()->create([
                    'product_variant_id' => $variant->id,
                    'quantity' => $qtyToAdd,
                    'is_selected' => true,
                ]);
            }
            $addedCount++;
        }

        if ($addedCount > 0) {
            return redirect()->route('cart.index')
                ->with('success', 'Barang dari pesanan berhasil dimasukkan kembali ke keranjang belanja Anda.');
        }

        return redirect()->back()
            ->with('error', 'Maaf, stok barang dari pesanan ini saat ini sedang habis atau tidak aktif.');
    }
}
