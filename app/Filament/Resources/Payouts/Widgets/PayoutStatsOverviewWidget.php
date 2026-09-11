<?php

namespace App\Filament\Resources\Payouts\Widgets;

use App\Enums\PayoutStatus;
use App\Models\Payout;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PayoutStatsOverviewWidget extends BaseWidget
{
    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $totalPaidAmount = (float) Payout::where('status', PayoutStatus::PAID)->sum('amount');
        $pendingQuery = Payout::where('status', PayoutStatus::PENDING);
        $pendingAmount = (float) $pendingQuery->sum('amount');
        $pendingCount = $pendingQuery->count();
        $totalPayoutsCount = Payout::count();
        $distinctSellersCount = Payout::distinct('seller_id')->count('seller_id');

        return [
            Stat::make('Total Dana Dicairkan', 'Rp '.number_format($totalPaidAmount, 0, ',', '.'))
                ->description('Sukses ditransfer ke rekening seller')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->chart([150000, 300000, 450000, 750000, max(1, (int) $totalPaidAmount)]),

            Stat::make('Menunggu Pencairan', 'Rp '.number_format($pendingAmount, 0, ',', '.'))
                ->description($pendingCount > 0 ? "{$pendingCount} permintaan perlu ditransfer" : 'Tidak ada antrean payout')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingCount > 0 ? 'warning' : 'gray')
                ->chart([0, 100000, 50000, max(0, (int) $pendingAmount)]),

            Stat::make('Total Payout Pesanan', "{$totalPayoutsCount} Transaksi")
                ->description('Akumulasi pesanan selesai & escrow')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary')
                ->chart([1, 2, 3, 4, max(1, $totalPayoutsCount)]),

            Stat::make('Toko Penerima Payout', "{$distinctSellersCount} Seller")
                ->description('Mitra creator & merchant terdaftar')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('info')
                ->chart([1, 2, 2, 3, max(1, $distinctSellersCount)]),
        ];
    }
}
