<?php

namespace App\Http\Controllers\Buyer;

use App\Enums\PayoutStatus;
use App\Http\Controllers\Controller;
use App\Models\EscrowBalance;
use App\Models\Order;
use App\Models\Payout;
use App\States\Order\Completed;
use App\States\Order\Delivered;
use App\States\Order\Shipped;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Auth::user()->orders()
            ->with(['items.variant.product.images', 'seller', 'payment', 'shipment'])
            ->latest()
            ->paginate(10);

        return view('orders.index', [
            'orders' => $orders,
            'title' => 'Pesanan Saya | WhiMarket',
            'activeTab' => 'pesanan',
        ]);
    }

    public function show(string $orderNumber): View
    {
        $order = Order::with(['items.variant.product.images', 'seller.user', 'payment', 'shipment', 'dispute'])
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
}
