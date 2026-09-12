<?php

namespace App\Filament\Widgets;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use LaBoiteACode\FilamentDashboardWidgets\Data\Metric;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\MetricWidget;

class SalesGoalWidget extends MetricWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 1;

    protected ?string $emptyStateHeading = 'Belum ada progres target';

    protected ?string $emptyStateDescription = 'Progres akan terisi mengikuti GMV terverifikasi.';

    protected function getMetric(): Metric
    {
        $realGmv = (int) Payment::where('status', PaymentStatus::VERIFIED)->sum('amount');
        $target = 25000000;
        $remaining = max(0, $target - $realGmv);
        $percentage = $target > 0 ? round(($realGmv / $target) * 100, 1) : 0;

        return Metric::make('Target GMV Kuartal Ini', $realGmv)
            ->formatUsing(fn ($val) => 'Rp '.number_format($val, 0, ',', '.'))
            ->description('Target: Rp '.number_format($target, 0, ',', '.').' · Sisa: Rp '.number_format($remaining, 0, ',', '.'))
            ->trend($percentage)
            ->trendLabel($percentage.'%')
            ->icon('heroicon-o-chart-bar-square')
            ->color('primary')
            ->sparkline($realGmv > 0 ? [14, 18, 17, 24, 30, 42, 54] : [0]);
    }
}
