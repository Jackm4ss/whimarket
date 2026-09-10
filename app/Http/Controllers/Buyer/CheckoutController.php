<?php

namespace App\Http\Controllers\Buyer;

use App\Enums\PaymentStatus;
use App\Enums\ProductStatus;
use App\Enums\SellerStatus;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\EscrowBalance;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PlatformSetting;
use App\Models\ProductVariant;
use App\Services\ShippingRateService;
use App\States\Order\PaymentVerification;
use App\States\Order\PendingPayment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('home')->with('error', 'Akun Administrator tidak dapat melakukan transaksi checkout.');
        }
        $cart = Cart::where('user_id', $user->id)->first();
        $items = $cart ? $cart->items()
            ->where('is_selected', true)
            ->with(['variant.product.images', 'variant.product.seller.user'])
            ->get() : collect();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Pilih minimal satu barang di keranjang untuk melanjutkan checkout.');
        }

        $inactiveItem = $items->first(fn ($i) => ! $i->variant?->product || $i->variant->product->status !== ProductStatus::ACTIVE || $i->variant->product->seller?->status !== SellerStatus::VERIFIED);
        if ($inactiveItem) {
            $cart->items()
                ->where(function ($q) {
                    $q->whereHas('variant.product', fn ($pq) => $pq->where('status', '!=', ProductStatus::ACTIVE))
                        ->orWhereHas('variant.product.seller', fn ($sq) => $sq->where('status', '!=', SellerStatus::VERIFIED));
                })
                ->update(['is_selected' => false]);

            $isSellerInactive = $inactiveItem->variant?->product?->seller && $inactiveItem->variant->product->seller->status !== SellerStatus::VERIFIED;
            $msg = $isSellerInactive
                ? "Toko penjual '{$inactiveItem->variant->product->seller->store_name}' sedang dinonaktifkan sehingga produk tidak dapat dibeli."
                : "Produk '".($inactiveItem->variant?->product?->name ?? 'Produk')."' sedang tidak aktif dan tidak dapat dibeli.";

            return redirect()->route('cart.index')->with('error', $msg);
        }
        $addresses = $user->addresses()->latest()->get();
        $defaultAddress = $addresses->firstWhere('is_default', true) ?? $addresses->first();

        $subtotal = $items->sum(fn ($i) => (float) $i->variant->price * $i->quantity);
        $defaultRate = ShippingRateService::calculate($defaultAddress?->province);
        $shippingCost = $defaultRate['cost'];
        $adminFee = PlatformSetting::getAdminFee();
        $grandTotal = $subtotal + $shippingCost + $adminFee;
        $addressesData = $addresses->map(function ($a) {
            $rate = ShippingRateService::calculate($a->province);

            return [
                'id' => $a->id,
                'recipient_name' => $a->recipient_name,
                'phone' => $a->phone,
                'full_address' => $a->full_address,
                'province' => $a->province,
                'city' => $a->city,
                'district' => $a->district,
                'postal_code' => $a->postal_code,
                'is_default' => (bool) $a->is_default,
                'shipping_cost' => $rate['cost'],
                'shipping_zone' => $rate['zone'],
                'shipping_etd' => $rate['etd'],
            ];
        });

        return view('checkout.index', [
            'items' => $items,
            'addresses' => $addresses,
            'addressesData' => $addressesData,
            'defaultAddress' => $defaultAddress,
            'subtotal' => $subtotal,
            'shippingCost' => $shippingCost,
            'defaultZone' => $defaultRate['zone'],
            'defaultEtd' => $defaultRate['etd'],
            'adminFee' => $adminFee,
            'grandTotal' => $grandTotal,
            'title' => 'Checkout Pesanan | WhiMarket',
            'activeTab' => 'checkout',
        ]);
    }

    public function process(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('home')->with('error', 'Akun Administrator tidak dapat melakukan transaksi checkout.');
        }
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

                    $isProductActive = $variant && $variant->product && $variant->product->status === ProductStatus::ACTIVE;
                    $isSellerActive = $variant?->product?->seller && $variant->product->seller->status === SellerStatus::VERIFIED;

                    if (! $isProductActive || ! $isSellerActive) {
                        $msg = (! $isSellerActive && $variant?->product?->seller)
                            ? "Toko penjual '{$variant->product->seller->store_name}' sedang dinonaktifkan sehingga produk tidak dapat dibeli."
                            : "Produk '".($variant?->product?->name ?? 'Produk')."' sedang tidak aktif dan tidak dapat dibeli.";
                        throw new \Exception($msg);
                    }

                    if ($variant->stock < $item->quantity) {
                        throw new \Exception("Stok untuk produk '{$variant->product->name}' ({$variant->name}) tidak mencukupi.");
                    }
                    // Decrement variant stock
                    $variant->decrement('stock', $item->quantity);

                    $subtotal = (float) $variant->price * $item->quantity;
                    $totalAmount += $subtotal;
                    $sellerId = $variant->product->seller_id;
                }

                $shippingRate = ShippingRateService::calculate($address->province);
                $shippingCost = $shippingRate['cost'];
                $adminFee = PlatformSetting::getAdminFee();
                $grandTotal = $totalAmount + $shippingCost + $adminFee;
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
                    'admin_fee' => $adminFee,
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

    public function showPayment(string $orderNumber): View|RedirectResponse
    {
        $order = Order::with(['items.variant.product.variants', 'seller', 'payment'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        if ($order->buyer_id !== auth()->id() && ! auth()->user()?->isAdmin()) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk melihat instruksi pembayaran pesanan ini.');
        }

        $isOutOfStock = $order->items->contains(function ($item) {
            return ($item->variant?->stock ?? 0) <= 0 || ($item->variant?->product?->total_stock ?? 0) <= 0;
        });

        return view('checkout.payment', [
            'order' => $order,
            'isOutOfStock' => $isOutOfStock,
            'title' => 'Instruksi Pembayaran #'.$order->order_number.' | WhiMarket',
            'activeTab' => 'checkout',
        ]);
    }

    public function uploadProof(Request $request, string $orderNumber): RedirectResponse
    {
        $order = Order::with(['items.variant.product.variants', 'payment'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        if ($order->buyer_id !== auth()->id() && ! auth()->user()?->isAdmin()) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengunggah bukti pembayaran pesanan ini.');
        }

        if (! in_array($order->status::$name, ['pending_payment', 'payment_verification'], true)) {
            return redirect()->route('orders.show', $order->order_number)
                ->with('error', 'Pesanan ini saat ini tidak membutuhkan pengunggahan bukti pembayaran.');
        }

        $isOutOfStock = $order->items->contains(function ($item) {
            return ($item->variant?->stock ?? 0) <= 0 || ($item->variant?->product?->total_stock ?? 0) <= 0;
        });

        if ($isOutOfStock) {
            return redirect()->route('orders.index')
                ->with('error', 'Maaf, stok barang pada pesanan ini sudah habis dari penjual. Pembayaran tidak dapat diproses.');
        }

        $validated = $request->validate([
            'sender_bank_name' => 'required|string|max:50',
            'sender_account_name' => 'required|string|max:100',
            'proof' => 'required|file|image|mimes:jpeg,png,jpg,webp|max:10240',
        ], [
            'sender_bank_name.required' => 'Nama bank pengirim wajib diisi.',
            'sender_bank_name.max' => 'Nama bank pengirim maksimal 50 karakter.',
            'sender_account_name.required' => 'Nama pemilik rekening wajib diisi.',
            'sender_account_name.max' => 'Nama pemilik rekening maksimal 100 karakter.',
            'proof.required' => 'Foto bukti transfer wajib diunggah.',
            'proof.file' => 'Bukti transfer harus berupa file.',
            'proof.image' => 'Bukti transfer harus berupa gambar yang valid.',
            'proof.mimes' => 'Format bukti transfer harus berupa file JPG, PNG, atau WEBP.',
            'proof.max' => 'Ukuran file bukti transfer tidak boleh melebihi 10MB.',
        ]);

        $proofFile = $request->file('proof');

        // Deep binary inspection: verify real image signature & dimensions to prevent disguised files/hacks
        $imageInfo = @getimagesize($proofFile->getPathname());
        if ($imageInfo !== false && in_array($imageInfo[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            // Map safe extension strictly from inspected binary mime, not user-supplied extension
            $extension = match ($imageInfo[2]) {
                IMAGETYPE_JPEG => 'jpg',
                IMAGETYPE_PNG => 'png',
                IMAGETYPE_WEBP => 'webp',
                default => 'jpg',
            };
        } else {
            // Fallback: Laravel's image validation already passed, use validated client extension
            $ext = strtolower($proofFile->getClientOriginalExtension() ?: 'jpg');
            $extension = in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true) ? $ext : 'jpg';
        }

        $filename = 'proof_'.$order->id.'_'.Str::random(32).'.'.$extension;
        $path = $proofFile->storeAs('payments', $filename, 'public');

        // Cleanup old proof file if exists to prevent storage accumulation/bottleneck
        if ($order->payment && $order->payment->proof_path && Storage::disk('public')->exists($order->payment->proof_path)) {
            Storage::disk('public')->delete($order->payment->proof_path);
        }

        $cleanBankName = strip_tags(trim($validated['sender_bank_name']));
        $cleanAccountName = strip_tags(trim($validated['sender_account_name']));

        $order->payment->update([
            'sender_bank_name' => $cleanBankName,
            'sender_account_name' => $cleanAccountName,
            'proof_path' => $path,
            'status' => PaymentStatus::PENDING_REVIEW,
        ]);

        // Transition order status to PaymentVerification
        if ($order->status->canTransitionTo(PaymentVerification::class)) {
            $order->status->transitionTo(PaymentVerification::class);
        }

        return redirect()->route('orders.show', $order->order_number)
            ->with('success', 'Bukti pembayaran berhasil diunggah! Admin WhiMarket sedang memverifikasi transfer Anda.');
    }
}
