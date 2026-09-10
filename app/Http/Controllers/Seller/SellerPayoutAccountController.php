<?php

namespace App\Http\Controllers\Seller;

use App\Enums\PayoutStatus;
use App\Http\Controllers\Controller;
use App\Models\Payout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SellerPayoutAccountController extends Controller
{
    /**
     * Indonesian popular commercial & Islamic banks list.
     */
    public const AVAILABLE_BANKS = [
        'BCA' => 'Bank Central Asia (BCA)',
        'Mandiri' => 'Bank Mandiri',
        'BRI' => 'Bank Rakyat Indonesia (BRI)',
        'BNI' => 'Bank Negara Indonesia (BNI)',
        'BSI' => 'Bank Syariah Indonesia (BSI)',
        'CIMB Niaga' => 'Bank CIMB Niaga',
        'Permata' => 'Bank Permata',
        'Danamon' => 'Bank Danamon',
        'Jago' => 'Bank Jago',
        'SeaBank' => 'SeaBank Indonesia',
        'BTN' => 'Bank Tabungan Negara (BTN)',
        'BTPN' => 'Bank BTPN / Jenius',
        'OCBC' => 'OCBC NISP',
        'Panin' => 'Bank Panin',
        'Muamalat' => 'Bank Muamalat',
    ];

    public function index(Request $request): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user && $user->isAdmin()) {
            return redirect('/admin/payouts')->with('info', 'Kelola pencairan dana seller melalui Admin Panel.');
        }

        $seller = $user?->seller;

        if (! $seller) {
            return redirect()->route('seller.register')
                ->with('info', 'Silakan aktifkan toko Anda terlebih dahulu.');
        }

        // Payout metrics
        $payoutsQuery = Payout::where('seller_id', $seller->id);

        $stats = [
            'total_paid' => (float) (clone $payoutsQuery)->where('status', PayoutStatus::PAID)->sum('amount'),
            'pending_amount' => (float) (clone $payoutsQuery)->whereIn('status', [PayoutStatus::PENDING, PayoutStatus::PROCESSING])->sum('amount'),
            'total_transactions' => (clone $payoutsQuery)->count(),
            'paid_count' => (clone $payoutsQuery)->where('status', PayoutStatus::PAID)->count(),
        ];

        $recentPayouts = (clone $payoutsQuery)
            ->with(['order.items'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('seller.payout-account.index', [
            'seller' => $seller,
            'stats' => $stats,
            'recentPayouts' => $recentPayouts,
            'availableBanks' => self::AVAILABLE_BANKS,
            'title' => 'Rekening Bank Pencairan Dana (Payout) | WhiMarket Seller',
            'activeTab' => 'seller-payout-account',
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $seller = $user?->seller;

        if (! $seller) {
            abort(403, 'Akses toko belum terdaftar.');
        }

        $validated = $request->validate([
            'bank_name' => 'required|string|max:50',
            'bank_account_number' => ['required', 'string', 'min:5', 'max:30', 'regex:/^[0-9\-]+$/'],
            'bank_account_name' => 'required|string|min:3|max:100',
            'confirm_agreement' => 'accepted',
        ], [
            'bank_name.required' => 'Silakan pilih atau masukkan nama bank tujuan pencairan.',
            'bank_account_number.required' => 'Nomor rekening bank wajib diisi.',
            'bank_account_number.min' => 'Nomor rekening bank minimal 5 digit angka.',
            'bank_account_number.max' => 'Nomor rekening bank maksimal 30 karakter.',
            'bank_account_number.regex' => 'Nomor rekening hanya boleh berupa angka dan tanda hubung.',
            'bank_account_name.required' => 'Nama pemilik rekening wajib diisi sesuai buku tabungan / KTP.',
            'bank_account_name.min' => 'Nama pemilik rekening minimal 3 karakter.',
            'confirm_agreement.accepted' => 'Anda wajib menyetujui pernyataan kepemilikan rekening yang sah.',
        ]);

        // Clean account number (strip unnecessary spaces)
        $cleanNumber = preg_replace('/\s+/', '', $validated['bank_account_number']);

        $seller->update([
            'bank_name' => trim($validated['bank_name']),
            'bank_account_number' => $cleanNumber,
            'bank_account_name' => trim($validated['bank_account_name']),
        ]);

        return redirect()->route('seller.payout-account.index')
            ->with('success', "Rekening pencairan dana berhasil diperbarui ke {$seller->bank_name} - {$seller->bank_account_number} a.n. {$seller->bank_account_name}. Seluruh pencairan hasil pesanan berikutnya akan ditransfer ke rekening ini.");
    }
}
