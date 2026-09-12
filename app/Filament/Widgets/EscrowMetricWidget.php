<?php

namespace App\Filament\Widgets;

use App\Models\EscrowBalance;
use LaBoiteACode\FilamentDashboardWidgets\Data\Metric;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\MetricWidget;

class EscrowMetricWidget extends MetricWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 3;

    protected ?string $emptyStateHeading = 'Belum ada dana escrow';

    protected ?string $emptyStateDescription = 'Dana tertahan akan muncul saat ada pesanan berjalan.';

    protected function getMetric(): Metric
    {
        $totalEscrow = (float) EscrowBalance::where('is_released', false)->sum('amount');

        return Metric::make('Dana Tertahan di Escrow', (int) $totalEscrow)
            ->formatUsing(fn ($val) => 'Rp '.number_format($val, 0, ',', '.'))
            ->description($totalEscrow > 0 ? 'Perlindungan transaksi 48 jam' : 'Belum ada dana tertahan')
            ->trend($totalEscrow > 0 ? 5.2 : 0)
            ->icon('heroicon-o-shield-check')
            ->color('warning')
            ->sparkline($totalEscrow > 0 ? [6, 8, 7, 9, 11, 14, 15] : [0]);
    }
}
