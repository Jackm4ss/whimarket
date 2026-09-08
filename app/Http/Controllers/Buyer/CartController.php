<?php

namespace App\Http\Controllers\Buyer;

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
    public function index(): View
    {
        if (! Auth::check()) {
            return view('cart', [
                'cart' => null,
                'items' => collect(),
                'groupedItems' => collect(),
                'selectedSubtotal' => 0,
                'title' => 'Keranjang Belanja | WhiMarket',
                'activeTab' => 'keranjang',
            ]);
        }

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $items = $cart->items()
            ->with(['variant.product.images', 'variant.product.seller.user'])
            ->get();
        $selectedSubtotal = $items->where('is_selected', true)->sum(fn ($i) => (float) $i->variant->price * $i->quantity);

        return view('cart', [
            'cart' => $cart,
            'items' => $items,
            'groupedItems' => $groupedItems,
            'selectedSubtotal' => $selectedSubtotal,
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
                    'redirect' => route('auth.google.redirect'),
                ], 401);
            }

            return redirect()->route('auth.google.redirect');
        }

        $validated = $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1',
            'buy_now' => 'nullable|boolean',
        ]);

        $variant = ProductVariant::findOrFail($validated['product_variant_id']);
        $qty = (int) ($validated['quantity'] ?? 1);

        if ($variant->stock < $qty) {
            $msg = 'Maaf, stok varian tidak mencukupi (sisa: '.$variant->stock.').';
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
        $item = CartItem::whereHas('cart', fn ($q) => $q->where('user_id', Auth::id()))
            ->with('variant')
            ->findOrFail($id);

        if ($request->has('quantity')) {
            $qty = max(1, min((int) $request->input('quantity'), $item->variant->stock));
            $item->update(['quantity' => $qty]);
        }

        if ($request->has('is_selected')) {
            $item->update(['is_selected' => $request->boolean('is_selected')]);
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
}
