<?php

namespace App\Filament\Resources\Orders\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalOrders = Order::count();
        $pendingProcessing = Order::where('status', 'like', '%processing%')
            ->orWhere('status', 'like', '%payment_verification%')
            ->count();
        $completedOrders = Order::where('status', 'like', '%delivered%')
            ->orWhere('status', 'like', '%completed%')
            ->count();
        $totalGmv = (float) Order::sum('grand_total');

        return [
            Stat::make('Total Pesanan Masuk', $totalOrders.' Pesanan')
                ->description('Seluruh pesanan tercatat di WhiMarket')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary')
                ->chart([2, 3, 2, 4, 3, 5, $totalOrders]),

            Stat::make('Perlu Tindakan Segera', $pendingProcessing.' Pesanan')
                ->description('Menunggu verifikasi & pengiriman')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingProcessing > 0 ? 'warning' : 'success')
                ->chart([1, 2, 1, 3, 2, 4, $pendingProcessing]),

            Stat::make('Pesanan Selesai', $completedOrders.' Pesanan')
                ->description('Terkirim & transaksi tuntas')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->chart([0, 1, 1, 2, 1, 2, $completedOrders]),

            Stat::make('Total Nilai Transaksi', 'Rp '.number_format($totalGmv, 0, ',', '.'))
                ->description('Akumulasi nilai seluruh pesanan')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info')
                ->chart([450000, 950000, 1500000, 2400000, 3100000, (int) $totalGmv]),
        ];
    }
}
