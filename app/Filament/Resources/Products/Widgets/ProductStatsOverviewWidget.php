<?php

namespace App\Filament\Resources\Products\Widgets;

use App\Enums\ProductStatus;
use App\Models\Product;
use App\Models\ProductVariant;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductStatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('status', ProductStatus::ACTIVE)->count();
        $totalStock = (int) ProductVariant::sum('stock');
        $catalogValuation = (float) Product::sum('price');

        return [
            Stat::make('Total Produk Katalog', $totalProducts.' Item')
                ->description('Koleksi pre-loved & merchandise')
                ->descriptionIcon('heroicon-m-tag')
                ->color('primary')
                ->chart([4, 6, 8, 12, 15, max(1, $totalProducts)]),

            Stat::make('Produk Aktif Tayang', $activeProducts.' Live')
                ->description('Siap dibeli oleh pelanggan')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([3, 5, 7, 10, 14, max(1, $activeProducts)]),

            Stat::make('Total Unit Inventaris', number_format($totalStock, 0, ',', '.').' Stok')
                ->description('Akumulasi seluruh varian produk')
                ->descriptionIcon('heroicon-m-cube')
                ->color('info')
                ->chart([50, 90, 140, 200, 250, max(1, $totalStock)]),

            Stat::make('Estimasi Nilai Katalog', 'Rp '.number_format($catalogValuation, 0, ',', '.'))
                ->description('Valuasi listing produk terdaftar')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning')
                ->chart([2, 4, 6, 8, 10, 12]),
        ];
    }
}
