<?php

namespace App\Filament\Resources\PlatformSettings\Widgets;

use App\Models\PlatformSetting;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PlatformSettingStatsOverviewWidget extends BaseWidget
{
    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $adminFee = PlatformSetting::getAdminFee();
        $isFeeActive = PlatformSetting::isAdminFeeActive();

        $freeShippingThreshold = (float) (PlatformSetting::where('key', 'free_shipping_min_order')->value('value') ?? 150000);
        $inspectionHours = (int) (PlatformSetting::where('key', 'inspection_deadline_hours')->value('value') ?? 48);

        return [
            Stat::make('Biaya Layanan Pembeli', $isFeeActive ? 'Rp '.number_format($adminFee, 0, ',', '.') : 'Bebas Fee (Rp 0)')
                ->description($isFeeActive ? 'Dikenakan per transaksi checkout' : 'Promo subsidi bebas biaya aktif')
                ->descriptionIcon('heroicon-m-receipt-percent')
                ->color($isFeeActive ? 'primary' : 'gray')
                ->chart([1000, 1500, 2000, max(1, (int) $adminFee)]),

            Stat::make('Status Pengenaan Fee', $isFeeActive ? 'Aktif Dikenakan' : 'Dinonaktifkan')
                ->description('Operasional penanganan escrow')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color($isFeeActive ? 'success' : 'gray')
                ->chart([1, 1, 1, $isFeeActive ? 1 : 0]),

            Stat::make('Minimal Belanja Subsidi', 'Rp '.number_format($freeShippingThreshold, 0, ',', '.'))
                ->description('Ambang batas potongan ongkir')
                ->descriptionIcon('heroicon-m-truck')
                ->color('info')
                ->chart([50000, 100000, max(1, (int) $freeShippingThreshold)]),

            Stat::make('Batas Inspeksi Barang', "{$inspectionHours} Jam")
                ->description('Tenggat komplain sebelum payout')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->chart([12, 24, 36, max(1, $inspectionHours)]),
        ];
    }
}
