<?php

namespace App\Filament\Resources\Payments\Widgets;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PaymentStatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalPayments = Payment::count();
        $pendingReview = Payment::where('status', PaymentStatus::PENDING_REVIEW)->count();
        $verifiedPayments = Payment::where('status', PaymentStatus::VERIFIED)->count();
        $totalVerifiedAmount = (float) Payment::where('status', PaymentStatus::VERIFIED)->sum('amount');

        return [
            Stat::make('Total Pembayaran Masuk', $totalPayments.' Transaksi')
                ->description('Seluruh bukti transfer tercatat')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('primary')
                ->chart([1, 2, 2, 3, 4, max(1, $totalPayments)]),

            Stat::make('Menunggu Verifikasi', $pendingReview.' Pending')
                ->description('Bukti transfer butuh validasi admin')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingReview > 0 ? 'warning' : 'success')
                ->chart([0, 1, 0, 2, 1, max(1, $pendingReview)]),

            Stat::make('Pembayaran Terverifikasi', $verifiedPayments.' Disetujui')
                ->description('Dana sah & escrow toko aktif')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->chart([1, 1, 2, 2, 3, max(1, $verifiedPayments)]),

            Stat::make('Total Dana Terverifikasi', 'Rp '.number_format($totalVerifiedAmount, 0, ',', '.'))
                ->description('Akumulasi dana pembayaran valid')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info')
                ->chart([450000, 1000000, 1800000, 2500000, max(100000, (int) $totalVerifiedAmount)]),
        ];
    }
}
