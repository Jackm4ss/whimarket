<?php

namespace App\Filament\Resources\ShippingZones\Pages;

use App\Filament\Resources\ShippingZones\ShippingZoneResource;
use App\Filament\Resources\ShippingZones\Widgets\ShippingZoneStatsOverviewWidget;
use App\Models\ShippingZone;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class ListShippingZones extends ListRecords
{
    protected static string $resource = ShippingZoneResource::class;

    public function getTitle(): string
    {
        return 'Tarif Logistik & Zonasi Wilayah';
    }

    public function getSubheading(): ?string
    {
        return 'Konfigurasi tarif pengiriman flat per zona, estimasi waktu tempuh (ETD), cakupan provinsi wilayah Indonesia, dan pengaturan promo subsidi gratis ongkir.';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ShippingZoneStatsOverviewWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Zona Pengiriman')
                ->icon(Heroicon::OutlinedPlusCircle),
        ];
    }

    public function getTabs(): array
    {
        $allCount = ShippingZone::count();
        $activeCount = ShippingZone::where('is_active', true)->count();
        $freeShippingCount = ShippingZone::where('is_free_shipping', true)->count();
        $inactiveCount = ShippingZone::where('is_active', false)->count();

        return [
            'all' => Tab::make('Semua Zona')
                ->icon(Heroicon::OutlinedTruck)
                ->badge($allCount)
                ->badgeColor('gray'),

            'active' => Tab::make('Zona Aktif')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->badge($activeCount)
                ->badgeColor('success')
                ->modifyQueryUsing(fn ($query) => $query->where('is_active', true)),

            'free_shipping' => Tab::make('Promo Gratis Ongkir')
                ->icon(Heroicon::OutlinedSparkles)
                ->badge($freeShippingCount)
                ->badgeColor('warning')
                ->modifyQueryUsing(fn ($query) => $query->where('is_free_shipping', true)),

            'inactive' => Tab::make('Nonaktif')
                ->icon(Heroicon::OutlinedXCircle)
                ->badge($inactiveCount)
                ->badgeColor('danger')
                ->modifyQueryUsing(fn ($query) => $query->where('is_active', false)),
        ];
    }
}
