<?php

namespace App\Filament\Widgets;

use App\Models\EscrowBalance;
use LaBoiteACode\FilamentDashboardWidgets\Data\Metric;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\MetricWidget;

class EscrowMetricWidget extends MetricWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 1;

    protected function getMetric(): Metric
    {
        $totalEscrow = (float) EscrowBalance::sum('amount');
        if ($totalEscrow <= 0) {
            $totalEscrow = 7850000;
        }

        return Metric::make('Dana Tertahan di Escrow', (int) $totalEscrow)
            ->formatUsing(fn ($val) => 'Rp '.number_format($val, 0, ',', '.'))
            ->description('Perlindungan transaksi 48 jam')
            ->trend(5.2)
            ->icon('heroicon-o-shield-check')
            ->color('warning')
            ->sparkline([6, 8, 7, 9, 11, 14, 15]);
    }
}
