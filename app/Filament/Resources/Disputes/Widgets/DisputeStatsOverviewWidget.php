<?php

namespace App\Filament\Resources\Disputes\Widgets;

use App\Models\Dispute;
use App\States\Dispute\ResolvedRefund;
use App\States\Dispute\ResolvedRejected;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DisputeStatsOverviewWidget extends BaseWidget
{
    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $totalDisputes = Dispute::count();
        $activeDisputes = Dispute::whereNotState('status', [ResolvedRefund::class, ResolvedRejected::class])->count();
        $refundedCount = Dispute::whereState('status', ResolvedRefund::class)->count();
        $rejectedCount = Dispute::whereState('status', ResolvedRejected::class)->count();

        return [
            Stat::make('Perlu Mediasi (Aktif)', "{$activeDisputes} Kasus")
                ->description($activeDisputes > 0 ? 'Menunggu pemeriksaan & keputusan admin' : 'Tidak ada sengketa pending')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($activeDisputes > 0 ? 'danger' : 'gray')
                ->chart([0, 1, 0, 1, max(0, $activeDisputes)]),

            Stat::make('Refund Pembeli (Disetujui)', "{$refundedCount} Kasus")
                ->description('Komplain dimenangkan oleh pembeli')
                ->descriptionIcon('heroicon-m-arrow-path-rounded-square')
                ->color('info')
                ->chart([0, 1, 2, 1, max(1, $refundedCount)]),

            Stat::make('Cairkan ke Penjual', "{$rejectedCount} Kasus")
                ->description('Komplain ditolak, dana ke seller')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->chart([1, 1, 2, 3, max(1, $rejectedCount)]),

            Stat::make('Total Seluruh Sengketa', "{$totalDisputes} Kasus")
                ->description('Akumulasi laporan komplain barang')
                ->descriptionIcon('heroicon-m-scale')
                ->color('primary')
                ->chart([1, 2, 2, 4, max(1, $totalDisputes)]),
        ];
    }
}
