<?php

namespace App\Filament\Resources\ShippingZones\Widgets;

use App\Models\ShippingZone;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ShippingZoneStatsOverviewWidget extends BaseWidget
{
    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $totalZones = ShippingZone::count();
        $activeZones = ShippingZone::where('is_active', true)->count();
        $inactiveZones = $totalZones - $activeZones;

        $minRate = (float) (ShippingZone::where('is_active', true)->min('rate') ?? 0);
        $maxRate = (float) (ShippingZone::where('is_active', true)->max('rate') ?? 0);
        $avgRate = (float) (ShippingZone::where('is_active', true)->avg('rate') ?? 0);

        $freeShippingCount = ShippingZone::where('is_free_shipping', true)->count();

        $allCoveredProvinces = ShippingZone::where('is_active', true)
            ->get()
            ->pluck('provinces')
            ->flatten()
            ->filter(fn ($p) => ! in_array($p, ['DKI Jakarta', 'DI Yogyakarta'], true)) // avoid counting alias double
            ->unique()
            ->count();

        $coveragePercent = $allCoveredProvinces > 0 ? min(100, round(($allCoveredProvinces / 38) * 100)) : 0;

        return [
            Stat::make('Total Zona Logistik', "{$activeZones} Zona Aktif")
                ->description($inactiveZones > 0 ? "{$inactiveZones} zona dinonaktifkan sementara" : "{$totalZones} kluster zonasi operasional")
                ->descriptionIcon('heroicon-m-globe-asia-australia')
                ->color('primary')
                ->chart([1, 2, 3, 4, max(1, $activeZones)]),

            Stat::make('Rentang Tarif Ongkir', $minRate > 0 ? 'Rp '.number_format($minRate, 0, ',', '.').' - Rp '.number_format($maxRate, 0, ',', '.') : 'Rp 0')
                ->description($avgRate > 0 ? 'Rata-rata: Rp '.number_format($avgRate, 0, ',', '.').' / kg' : 'Tarif belum dikonfigurasi')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info')
                ->chart([max(1, (int) $minRate), max(1, (int) $avgRate), max(1, (int) $maxRate)]),

            Stat::make('Subsidi Bebas Ongkir', "{$freeShippingCount} Zona Promo")
                ->description($freeShippingCount > 0 ? 'Subsidi tarif ongkir aktif untuk pembeli' : 'Semua zona gunakan tarif reguler')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color($freeShippingCount > 0 ? 'success' : 'gray')
                ->chart([0, 1, max(0, $freeShippingCount)]),

            Stat::make('Cakupan Wilayah', min(38, $allCoveredProvinces).' / 38 Provinsi')
                ->description("Jangkauan pengiriman {$coveragePercent}% nasional")
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('primary')
                ->chart([10, 20, 30, min(38, $allCoveredProvinces)]),
        ];
    }
}
