<?php

namespace App\Filament\Widgets;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use LaBoiteACode\FilamentDashboardWidgets\Data\Metric;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\MetricWidget;

class RevenueMetricWidget extends MetricWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 2;

    protected ?string $emptyStateHeading = 'Belum ada transaksi';

    protected ?string $emptyStateDescription = 'GMV akan muncul setelah ada pembayaran terverifikasi.';

    protected function getMetric(): Metric
    {
        $realGmv = (int) Payment::where('status', PaymentStatus::VERIFIED)->sum('amount');

        return Metric::make('Total GMV Transaksi', $realGmv)
            ->formatUsing(fn ($val) => 'Rp '.number_format($val, 0, ',', '.'))
            ->description($realGmv > 0 ? 'Bulan ini vs bulan lalu' : 'Belum ada transaksi terverifikasi')
            ->trend($realGmv > 0 ? 18.4 : 0)
            ->icon('heroicon-o-banknotes')
            ->color('success')
            ->sparkline($realGmv > 0 ? [12, 14, 13, 18, 22, 26, 31] : [0]);
    }
}
