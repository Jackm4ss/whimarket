<?php

namespace App\Http\Controllers\Buyer;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\EscrowBalance;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ProductVariant;
use App\States\Order\PaymentVerification;
use App\States\Order\PendingPayment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();
        $items = $cart ? $cart->items()
            ->where('is_selected', true)
            ->with(['variant.product.images', 'variant.product.seller.user'])
            ->get() : collect();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Pilih minimal satu barang di keranjang untuk melanjutkan checkout.');
        }

        $addresses = $user->addresses()->latest()->get();
        $defaultAddress = $addresses->firstWhere('is_default', true) ?? $addresses->first();

        $subtotal = $items->sum(fn ($i) => (float) $i->variant->price * $i->quantity);
        $shippingCost = 15000;
        $grandTotal = $subtotal + $shippingCost;

        return view('checkout.index', [
            'items' => $items,
            'addresses' => $addresses,
            'defaultAddress' => $defaultAddress,
            'subtotal' => $subtotal,
            'shippingCost' => $shippingCost,
            'grandTotal' => $grandTotal,
            'title' => 'Checkout Pesanan | WhiMarket',
            'activeTab' => 'checkout',
        ]);
    }

    public function process(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->firstOrFail();
        $items = $cart->items()->where('is_selected', true)->with('variant.product.seller')->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Tidak ada barang terpilih untuk checkout.');
        }

        $validated = $request->validate([
            'address_id' => 'required|exists:addresses,id',
        ]);

        $address = Address::where('user_id', $user->id)->findOrFail($validated['address_id']);

        try {
            $order = DB::transaction(function () use ($user, $items, $cart, $address) {
                $variantIds = $items->pluck('product_variant_id')->sort()->values()->all();

                // Pessimistic locking in ascending order to prevent deadlocks
                $lockedVariants = ProductVariant::whereIn('id', $variantIds)
                    ->orderBy('id', 'asc')
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $totalAmount = 0;
                $sellerId = null;

                foreach ($items as $item) {
                    $variant = $lockedVariants->get($item->product_variant_id);

                    if (! $variant || $variant->stock < $item->quantity) {
                        throw new \Exception("Stok untuk produk '{$variant->product->name}' ({$variant->name}) tidak mencukupi.");
                    }

                    // Decrement variant stock
                    $variant->decrement('stock', $item->quantity);

                    $subtotal = (float) $variant->price * $item->quantity;
                    $totalAmount += $subtotal;
                    $sellerId = $variant->product->seller_id;
                }

                $shippingCost = 15000;
                $grandTotal = $totalAmount + $shippingCost;
                $orderNumber = 'WHI-'.date('Ymd').'-'.strtoupper(Str::random(6));

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'buyer_id' => $user->id,
                    'seller_id' => $sellerId,
                    'address_snapshot' => [
                        'recipient_name' => $address->recipient_name,
                        'phone' => $address->phone,
                        'full_address' => $address->full_address,
                        'province' => $address->province,
                        'city' => $address->city,
                        'district' => $address->district,
                        'postal_code' => $address->postal_code,
                    ],
                    'total_amount' => $totalAmount,
                    'shipping_cost' => $shippingCost,
                    'grand_total' => $grandTotal,
                    'status' => PendingPayment::class,
                ]);

                // Create Order Items
                foreach ($items as $item) {
                    $variant = $lockedVariants->get($item->product_variant_id);
                    $subtotal = (float) $variant->price * $item->quantity;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_variant_id' => $variant->id,
                        'product_name_snapshot' => $variant->product->name,
                        'variant_name_snapshot' => $variant->name,
                        'price_snapshot' => $variant->price,
                        'quantity' => $item->quantity,
                        'subtotal' => $subtotal,
                    ]);
                }

                // Delete checked-out items from cart
                $cart->items()->where('is_selected', true)->delete();

                // Create Payment entry
                Payment::create([
                    'order_id' => $order->id,
                    'bank_destination' => 'BCA',
                    'amount' => $grandTotal,
                    'status' => PaymentStatus::UNPAID,
                ]);

                // Create Escrow Balance entry (funds held)
                EscrowBalance::create([
                    'order_id' => $order->id,
                    'seller_id' => $sellerId,
                    'amount' => $totalAmount,
                    'is_released' => false,
                ]);

                return $order;
            });

            return redirect()->route('payment.show', $order->order_number)
                ->with('success', 'Pesanan berhasil dibuat! Silakan transfer pembayaran.');
        } catch (\Exception $e) {
            return redirect()->route('checkout.index')->with('error', $e->getMessage());
        }
    }

    public function showPayment(string $orderNumber): View
    {
        $order = Order::with(['items', 'seller', 'payment'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('checkout.payment', [
            'order' => $order,
            'title' => 'Instruksi Pembayaran #'.$order->order_number.' | WhiMarket',
            'activeTab' => 'checkout',
        ]);
    }

    public function uploadProof(Request $request, string $orderNumber): RedirectResponse
    {
        $order = Order::with('payment')->where('order_number', $orderNumber)->firstOrFail();

        $validated = $request->validate([
            'sender_bank_name' => 'required|string|max:50',
            'sender_account_name' => 'required|string|max:100',
            'proof' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $proofFile = $request->file('proof');
        $filename = 'proof_'.$order->id.'_'.Str::uuid().'.'.$proofFile->getClientOriginalExtension();
        $path = $proofFile->storeAs('payments', $filename, 'public');

        $order->payment->update([
            'sender_bank_name' => $validated['sender_bank_name'],
            'sender_account_name' => $validated['sender_account_name'],
            'proof_path' => $path,
            'status' => PaymentStatus::PENDING_REVIEW,
        ]);

        // Transition order status to PaymentVerification
        if ($order->status->canTransitionTo(PaymentVerification::class)) {
            $order->status->transitionTo(PaymentVerification::class);
        }

        return redirect()->route('orders.show', $order->order_number)
            ->with('success', 'Bukti pembayaran berhasil diunggah! Admin WhiMarket sedang memverifikasi transfer kamu.');
    }
}
