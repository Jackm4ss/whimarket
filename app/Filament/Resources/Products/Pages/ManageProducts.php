<?php

namespace App\Filament\Resources\Products\Pages;

use App\Enums\ProductStatus;
use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Products\Widgets\ProductStatsOverviewWidget;
use App\Models\Product;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class ManageProducts extends ManageRecords
{
    protected static string $resource = ProductResource::class;

    protected ?string $subheading = 'Katalog & moderasi produk mitra seller, kelola stok varian, status tayang, serta kendalikan mutu listing pre-loved & merchandise.';

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ProductStatsOverviewWidget::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Produk')
                ->icon(Heroicon::OutlinedTag)
                ->badge(Product::count())
                ->badgeColor('gray'),

            'active' => Tab::make('Aktif Tayang')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->badge(Product::where('status', ProductStatus::ACTIVE)->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn ($query) => $query->where('status', ProductStatus::ACTIVE)),

            'inactive' => Tab::make('Nonaktif')
                ->icon(Heroicon::OutlinedPauseCircle)
                ->badge(Product::where('status', '!=', ProductStatus::ACTIVE)->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn ($query) => $query->where('status', '!=', ProductStatus::ACTIVE)),
        ];
    }
}
