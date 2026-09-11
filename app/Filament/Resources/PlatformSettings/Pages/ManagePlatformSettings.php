<?php

namespace App\Filament\Resources\PlatformSettings\Pages;

use App\Filament\Resources\PlatformSettings\PlatformSettingResource;
use App\Filament\Resources\PlatformSettings\Widgets\PlatformSettingStatsOverviewWidget;
use App\Models\PlatformSetting;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class ManagePlatformSettings extends ManageRecords
{
    protected static string $resource = PlatformSettingResource::class;

    public function getTitle(): string
    {
        return 'Pengaturan Sistem & Biaya Layanan';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola parameter operasional marketplace: biaya layanan transaksi (fee admin), ambang batas promo ongkir, batas waktu transfer, dan jendela waktu mediasi escrow.';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PlatformSettingStatsOverviewWidget::class,
        ];
    }

    public function getTabs(): array
    {
        $allCount = PlatformSetting::count();
        $activeCount = PlatformSetting::where('is_active', true)->count();
        $inactiveCount = PlatformSetting::where('is_active', false)->count();

        return [
            'all' => Tab::make('Semua Pengaturan')
                ->icon(Heroicon::OutlinedCog6Tooth)
                ->badge($allCount)
                ->badgeColor('gray'),

            'active' => Tab::make('Parameter Aktif')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->badge($activeCount)
                ->badgeColor('success')
                ->modifyQueryUsing(fn ($query) => $query->where('is_active', true)),

            'inactive' => Tab::make('Nonaktif / Bebas Biaya')
                ->icon(Heroicon::OutlinedSparkles)
                ->badge($inactiveCount)
                ->badgeColor('warning')
                ->modifyQueryUsing(fn ($query) => $query->where('is_active', false)),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
