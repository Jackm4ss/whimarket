<?php

namespace App\Filament\Resources\Sellers\Pages;

use App\Enums\SellerStatus;
use App\Filament\Resources\Sellers\SellerResource;
use App\Filament\Resources\Sellers\Widgets\SellerStatsOverviewWidget;
use App\Models\Seller;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class ManageSellers extends ManageRecords
{
    protected static string $resource = SellerResource::class;

    protected ?string $subheading = 'Pantau toko seller & content creator, verifikasi pendaftaran mitra VIP, serta kelola informasi rekening pencairan dana (payout).';

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SellerStatsOverviewWidget::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Toko')
                ->icon(Heroicon::OutlinedBuildingStorefront)
                ->badge(Seller::count())
                ->badgeColor('gray'),
            'verified' => Tab::make('Aktif (Live)')
                ->icon(Heroicon::OutlinedCheckBadge)
                ->badge(Seller::where('status', SellerStatus::VERIFIED)->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn ($query) => $query->where('status', SellerStatus::VERIFIED)),

            'suspended' => Tab::make('Nonaktif')
                ->icon(Heroicon::OutlinedNoSymbol)
                ->badge(Seller::where('status', SellerStatus::SUSPENDED)->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn ($query) => $query->where('status', SellerStatus::SUSPENDED)),

            'pending' => Tab::make('Menunggu Moderasi')
                ->icon(Heroicon::OutlinedClock)
                ->badge(Seller::where('status', SellerStatus::PENDING)->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn ($query) => $query->where('status', SellerStatus::PENDING)),
        ];
    }
}
