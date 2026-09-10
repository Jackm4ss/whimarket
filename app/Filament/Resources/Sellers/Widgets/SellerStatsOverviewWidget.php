<?php

namespace App\Filament\Resources\Sellers\Widgets;

use App\Enums\SellerStatus;
use App\Models\Product;
use App\Models\Seller;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SellerStatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalSellers = Seller::count();
        $verifiedSellers = Seller::where('status', SellerStatus::VERIFIED)->count();
        $suspendedSellers = Seller::where('status', SellerStatus::SUSPENDED)->count();
        $totalProducts = Product::count();

        return [
            Stat::make('Total Mitra Seller', $totalSellers.' Toko')
                ->description('Creator & merchant terdaftar')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('primary')
                ->chart([3, 5, 6, 7, 8, max(1, $totalSellers)]),

            Stat::make('Toko Aktif (Live)', $verifiedSellers.' Toko')
                ->description('Aktif melayani transaksi')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->chart([2, 4, 5, 6, 7, max(1, $verifiedSellers)]),

            Stat::make('Toko Nonaktif', $suspendedSellers.' Toko')
                ->description($suspendedSellers > 0 ? 'Toko dinonaktifkan sementara' : 'Semua toko aktif normal')
                ->descriptionIcon('heroicon-m-no-symbol')
                ->color($suspendedSellers > 0 ? 'warning' : 'gray')
                ->chart([0, 1, 0, 1, 0, max(0, $suspendedSellers)]),

            Stat::make('Total Produk Tayang', $totalProducts.' Item')
                ->description('Katalog pre-loved & merch')
                ->descriptionIcon('heroicon-m-tag')
                ->color('info')
                ->chart([5, 8, 12, 14, 18, max(1, $totalProducts)]),
        ];
    }
}
