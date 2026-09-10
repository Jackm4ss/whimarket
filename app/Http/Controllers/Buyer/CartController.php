<?php

namespace App\Http\Controllers\Buyer;

use App\Enums\ProductStatus;
use App\Enums\SellerStatus;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Akun Administrator tidak dapat menggunakan keranjang belanja.');
        }

        if (! Auth::check()) {
            return view('cart', [
                'cart' => null,
                'items' => collect(),
                'groupedItems' => collect(),
                'selectedSubtotal' => 0,
                'cartConfigItems' => [],
                'title' => 'Keranjang Belanja | WhiMarket',
                'activeTab' => 'keranjang',
            ]);
        }

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        // Auto-unselect any cart items whose product or seller is inactive
        $cart->items()
            ->where(function ($q) {
                $q->whereHas('variant.product', fn ($pq) => $pq->where('status', '!=', ProductStatus::ACTIVE))
                    ->orWhereHas('variant.product.seller', fn ($sq) => $sq->where('status', '!=', SellerStatus::VERIFIED));
            })
            ->where('is_selected', true)
            ->update(['is_selected' => false]);
        $items = $cart->items()
            ->with(['variant.product.images', 'variant.product.seller.user'])
            ->get();
        $groupedItems = $items->groupBy(fn ($item) => $item->variant?->product?->seller?->store_name ?? 'WhiMarket Creator');
        $selectedSubtotal = $items->where('is_selected', true)->sum(fn ($i) => (float) $i->variant->price * $i->quantity);
        $cartConfigItems = $items->map(fn ($i) => [
            'id' => $i->id,
            'quantity' => (int) $i->quantity,
            'is_selected' => (bool) $i->is_selected,
            'price' => (float) $i->variant->price,
            'stock' => (int) $i->variant->stock,
            'is_active' => $i->variant?->product?->status === ProductStatus::ACTIVE
                && ($i->variant?->product?->seller?->status === SellerStatus::VERIFIED),
        ])->values()->all();

        return view('cart', [
            'cart' => $cart,
            'items' => $items,
            'groupedItems' => $groupedItems,
            'selectedSubtotal' => $selectedSubtotal,
            'cartConfigItems' => $cartConfigItems,
            'title' => 'Keranjang Belanja | WhiMarket',
            'activeTab' => 'keranjang',
        ]);
    }

    public function add(Request $request): JsonResponse|RedirectResponse
    {
        if (! Auth::check()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => 'Unauthenticated',
                    'redirect' => route('login'),
                ], 401);
            }

            return redirect()->route('login');
        }

        if (Auth::user()->isAdmin()) {
            $msg = 'Akun Administrator tidak dapat melakukan transaksi pembelian.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 403);
            }

            return redirect()->back()->with('error', $msg);
        }

        $validated = $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1',
            'buy_now' => 'nullable|boolean',
        ]);

        $variant = ProductVariant::with('product.seller')->findOrFail($validated['product_variant_id']);

        $isProductActive = $variant->product && $variant->product->status === ProductStatus::ACTIVE;
        $isSellerActive = $variant->product?->seller && $variant->product->seller->status === SellerStatus::VERIFIED;

        if (! $isProductActive || ! $isSellerActive) {
            $msg = (! $isSellerActive)
                ? 'Maaf, toko penjual produk ini sedang dinonaktifkan sehingga produk tidak dapat dibeli.'
                : 'Maaf, produk ini sedang tidak aktif dan tidak dapat dibeli.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }

            return redirect()->back()->with('error', $msg);
        }

        $sellerUserId = $variant->product?->seller?->user_id;
        if ($sellerUserId && Auth::id() === $sellerUserId) {
            $msg = 'Anda tidak dapat membeli produk dari toko Anda sendiri.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }

            return redirect()->back()->with('error', $msg);
        }

        $qty = (int) ($validated['quantity'] ?? 1);

        if ($variant->stock <= 0 || $variant->stock < $qty) {
            $msg = $variant->stock <= 0 ? 'Maaf, stok produk ini sudah habis.' : 'Maaf, stok varian tidak mencukupi (sisa: '.$variant->stock.').';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }

            return redirect()->back()->with('error', $msg);
        }

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_variant_id', $variant->id)
            ->first();

        if ($item) {
            $newQty = min($item->quantity + $qty, $variant->stock);
            $item->update([
                'quantity' => $newQty,
                'is_selected' => true,
            ]);
        } else {
            $item = CartItem::create([
                'cart_id' => $cart->id,
                'product_variant_id' => $variant->id,
                'quantity' => $qty,
                'is_selected' => true,
            ]);
        }

        if ($request->boolean('buy_now')) {
            // Uncheck other items if buy now
            CartItem::where('cart_id', $cart->id)
                ->where('id', '!=', $item->id)
                ->update(['is_selected' => false]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect' => route('checkout.index'),
                ]);
            }

            return redirect()->route('checkout.index');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil ditambahkan ke keranjang!',
                'cart_count' => $cart->items()->count(),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil masuk ke keranjang!');
    }

    public function updateItem(Request $request, int $id): JsonResponse
    {
        if (Auth::user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Akun Administrator tidak dapat menggunakan keranjang belanja.'], 403);
        }

        $item = CartItem::whereHas('cart', fn ($q) => $q->where('user_id', Auth::id()))
            ->with(['variant.product.seller'])
            ->findOrFail($id);

        if ($request->has('quantity')) {
            $qty = max(1, min((int) $request->input('quantity'), $item->variant->stock));
            $item->update(['quantity' => $qty]);
        }

        if ($request->has('is_selected')) {
            $isSelected = $request->boolean('is_selected');
            $isProductActive = $item->variant?->product && $item->variant->product->status === ProductStatus::ACTIVE;
            $isSellerActive = $item->variant?->product?->seller && $item->variant->product->seller->status === SellerStatus::VERIFIED;

            if ($isSelected && (! $isProductActive || ! $isSellerActive)) {
                $msg = (! $isSellerActive)
                    ? 'Toko penjual sedang dinonaktifkan sehingga barang ini tidak dapat dipilih untuk checkout.'
                    : 'Produk ini sedang tidak aktif dan tidak dapat dipilih untuk checkout.';

                return response()->json([
                    'success' => false,
                    'message' => $msg,
                ], 422);
            }
            $item->update(['is_selected' => $isSelected]);
        }

        $cart = $item->cart;
        $items = $cart->items()->with('variant')->get();
        $selectedSubtotal = $items->where('is_selected', true)->sum(fn ($i) => (float) $i->variant->price * $i->quantity);

        return response()->json([
            'success' => true,
            'quantity' => $item->quantity,
            'is_selected' => $item->is_selected,
            'item_subtotal' => (float) $item->variant->price * $item->quantity,
            'selected_subtotal' => $selectedSubtotal,
            'selected_count' => $items->where('is_selected', true)->count(),
        ]);
    }

    public function removeItem(int $id): JsonResponse|RedirectResponse
    {
        if (Auth::user()->isAdmin()) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Akun Administrator tidak dapat menggunakan keranjang belanja.'], 403);
            }

            return redirect()->route('home')->with('error', 'Akun Administrator tidak dapat menggunakan keranjang belanja.');
        }
        $item = CartItem::whereHas('cart', fn ($q) => $q->where('user_id', Auth::id()))
            ->findOrFail($id);

        $item->delete();

        if (request()->wantsJson()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            $items = $cart ? $cart->items()->with('variant')->get() : collect();
            $selectedSubtotal = $items->where('is_selected', true)->sum(fn ($i) => (float) $i->variant->price * $i->quantity);

            return response()->json([
                'success' => true,
                'cart_count' => $items->count(),
                'selected_subtotal' => $selectedSubtotal,
            ]);
        }

        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function selectAll(Request $request): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        if (Auth::user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Akun Administrator tidak dapat menggunakan keranjang belanja.'], 403);
        }

        $isSelected = $request->boolean('is_selected', true);
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        if ($isSelected) {
            $cart->items()
                ->whereHas('variant.product', fn ($pq) => $pq->where('status', ProductStatus::ACTIVE))
                ->whereHas('variant.product.seller', fn ($sq) => $sq->where('status', SellerStatus::VERIFIED))
                ->update(['is_selected' => true]);

            $cart->items()
                ->where(function ($q) {
                    $q->whereHas('variant.product', fn ($pq) => $pq->where('status', '!=', ProductStatus::ACTIVE))
                        ->orWhereHas('variant.product.seller', fn ($sq) => $sq->where('status', '!=', SellerStatus::VERIFIED));
                })
                ->update(['is_selected' => false]);
        } else {
            $cart->items()->update(['is_selected' => false]);
        }

        $items = $cart->items()->with('variant')->get();
        $selectedSubtotal = $items->where('is_selected', true)->sum(fn ($i) => (float) $i->variant->price * $i->quantity);

        return response()->json([
            'success' => true,
            'selected_subtotal' => $selectedSubtotal,
            'selected_count' => $items->where('is_selected', true)->count(),
        ]);
    }
}
