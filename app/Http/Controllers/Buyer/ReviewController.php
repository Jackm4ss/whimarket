<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductReview;
use App\States\Order\Completed;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function create(string $orderNumber, Request $request): View|RedirectResponse
    {
        $order = Order::with(['items.variant.product.images', 'items.review', 'seller.user'])
            ->where('order_number', $orderNumber)
            ->where('buyer_id', Auth::id())
            ->firstOrFail();

        // Invariant: Order must be completed
        if (! ($order->status instanceof Completed || $order->status->equals(Completed::class))) {
            return redirect()->route('orders.show', $order->order_number)
                ->with('error', 'Ulasan hanya dapat diberikan setelah pesanan berstatus Selesai.');
        }

        $targetItemId = $request->query('item_id');

        return view('reviews.create', [
            'order' => $order,
            'targetItemId' => $targetItemId ? (int) $targetItemId : null,
            'title' => 'Beri Ulasan Pesanan #'.$order->order_number.' | WhiMarket',
            'activeTab' => 'pesanan',
        ]);
    }

    public function store(Request $request, string $orderNumber): JsonResponse|RedirectResponse
    {
        $order = Order::with('items.variant.product')
            ->where('order_number', $orderNumber)
            ->where('buyer_id', Auth::id())
            ->firstOrFail();

        // Invariant: Order must be completed
        if (! ($order->status instanceof Completed || $order->status->equals(Completed::class))) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ulasan hanya dapat diberikan setelah pesanan berstatus Selesai.',
                ], 422);
            }

            return redirect()->route('orders.show', $order->order_number)
                ->with('error', 'Ulasan hanya dapat diberikan setelah pesanan berstatus Selesai.');
        }

        $validated = $request->validate([
            'order_item_id' => 'required|integer|exists:order_items,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'video' => 'nullable|file|mimes:mp4,mov,webm,avi|max:51200',
        ], [
            'order_item_id.required' => 'Barang yang diulas harus dipilih.',
            'rating.required' => 'Rating bintang wajib dipilih (1 - 5 bintang).',
            'rating.min' => 'Rating minimal 1 bintang.',
            'rating.max' => 'Rating maksimal 5 bintang.',
            'comment.max' => 'Ulasan maksimal 2000 karakter.',
            'photos.max' => 'Maksimal 5 foto ulasan.',
            'photos.*.image' => 'File foto harus berupa gambar.',
            'photos.*.mimes' => 'Format foto harus JPEG, PNG, JPG, atau WEBP.',
            'photos.*.max' => 'Ukuran setiap foto maksimal 5MB.',
            'video.file' => 'File video unboxing tidak valid.',
            'video.mimes' => 'Format video harus MP4, MOV, WEBM, atau AVI.',
            'video.max' => 'Ukuran video maksimal 50MB.',
        ]);

        $orderItem = OrderItem::with('variant.product')
            ->where('id', $validated['order_item_id'])
            ->where('order_id', $order->id)
            ->first();

        if (! $orderItem) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Barang pesanan tidak ditemukan.',
                ], 404);
            }

            return back()->with('error', 'Barang pesanan tidak ditemukan.');
        }

        // Prevent duplicate reviews for the same order item
        if (ProductReview::where('order_item_id', $orderItem->id)->exists()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memberikan ulasan untuk produk ini.',
                ], 422);
            }

            return back()->with('info', 'Anda sudah memberikan ulasan untuk produk ini.');
        }

        $productId = $orderItem->variant?->product_id;
        if (! $productId) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data produk sudah tidak tersedia.',
                ], 422);
            }

            return back()->with('error', 'Data produk sudah tidak tersedia.');
        }

        // Handle uploaded review photos
        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                if ($photo->isValid()) {
                    $filename = 'review_'.$order->id.'_'.$orderItem->id.'_'.Str::uuid().'.'.$photo->getClientOriginalExtension();
                    $stored = $photo->storeAs('reviews', $filename, 'public');
                    $photoPaths[] = '/storage/'.$stored;
                }
            }
        }

        // Handle uploaded review video
        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoFile = $request->file('video');
            if ($videoFile->isValid()) {
                $filename = 'review_vid_'.$order->id.'_'.$orderItem->id.'_'.Str::uuid().'.'.$videoFile->getClientOriginalExtension();
                $stored = $videoFile->storeAs('reviews/videos', $filename, 'public');
                $videoPath = '/storage/'.$stored;
            }
        }

        $review = ProductReview::create([
            'order_id' => $order->id,
            'order_item_id' => $orderItem->id,
            'product_id' => $productId,
            'user_id' => Auth::id(),
            'rating' => (int) $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'photos' => ! empty($photoPaths) ? $photoPaths : null,
            'video' => $videoPath,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Ulasan berhasil dikirim! Terima kasih atas feedback-nya.',
                'review' => $review,
            ]);
        }

        return redirect()->route('orders.show', $order->order_number)
            ->with('success', 'Ulasan berhasil dikirim! Terima kasih atas feedback-nya.');
    }
}
