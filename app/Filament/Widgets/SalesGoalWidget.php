<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use LaBoiteACode\FilamentDashboardWidgets\Data\Metric;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\MetricWidget;

class SalesGoalWidget extends MetricWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 1;

    protected function getMetric(): Metric
    {
        $realGmv = (int) Payment::whereIn('status', ['paid', 'verified'])->sum('amount');
        $current = max($realGmv, 18450000);
        $target = 25000000;
        $remaining = max(0, $target - $current);
        $percentage = round(($current / $target) * 100, 1);

        return Metric::make('Target GMV Kuartal Ini', (int) $current)
            ->formatUsing(fn ($val) => 'Rp '.number_format($val, 0, ',', '.'))
            ->description('Target: Rp '.number_format($target, 0, ',', '.').' · Sisa: Rp '.number_format($remaining, 0, ',', '.'))
            ->trend($percentage)
            ->trendLabel($percentage.'%')
            ->icon('heroicon-o-chart-bar-square')
            ->color('primary')
            ->sparkline([14, 18, 17, 24, 30, 42, 54]);
    }
}
