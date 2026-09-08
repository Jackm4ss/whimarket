<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App\Models\Order;
use App\States\Dispute\OpenDispute;
use App\States\Order\Disputed;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DisputeController extends Controller
{
    public function create(string $orderNumber): View|RedirectResponse
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('buyer_id', Auth::id())
            ->firstOrFail();

        if ($order->dispute) {
            return redirect()->route('orders.show', $order->order_number)
                ->with('info', 'Sengketa untuk pesanan ini telah diajukan.');
        }

        return view('disputes.create', [
            'order' => $order,
            'title' => 'Ajukan Komplain / Sengketa #'.$order->order_number.' | WhiMarket',
            'activeTab' => 'pesanan',
        ]);
    }

    public function store(Request $request, string $orderNumber): RedirectResponse
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('buyer_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'reason' => 'required|string|max:100',
            'description' => 'required|string|max:2000',
            'evidence.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'video_unboxing' => 'nullable|file|mimes:mp4,mov,avi,webm|max:51200',
        ]);

        $evidencePaths = [];
        if ($request->hasFile('evidence')) {
            foreach ($request->file('evidence') as $file) {
                $filename = 'dispute_'.$order->id.'_'.Str::uuid().'.'.$file->getClientOriginalExtension();
                $evidencePaths[] = $file->storeAs('disputes/evidence', $filename, 'public');
            }
        }

        $videoPath = null;
        if ($request->hasFile('video_unboxing')) {
            $videoFile = $request->file('video_unboxing');
            $filename = 'unboxing_'.$order->id.'_'.Str::uuid().'.'.$videoFile->getClientOriginalExtension();
            $videoPath = $videoFile->storeAs('disputes/videos', $filename, 'public');
        }

        Dispute::create([
            'order_id' => $order->id,
            'buyer_id' => Auth::id(),
            'reason' => $validated['reason'],
            'description' => $validated['description'],
            'buyer_evidence_paths' => $evidencePaths,
            'video_unboxing_path' => $videoPath,
            'status' => OpenDispute::class,
        ]);

        // Freeze countdown & transition order status
        if ($order->status->canTransitionTo(Disputed::class)) {
            $order->status->transitionTo(Disputed::class);
        }

        return redirect()->route('orders.show', $order->order_number)
            ->with('success', 'Komplain berhasil diajukan! Penjual memiliki waktu 48 jam untuk memberikan tanggapan.');
    }
}
