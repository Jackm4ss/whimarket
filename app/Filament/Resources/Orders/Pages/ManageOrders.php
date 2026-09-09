<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Orders\Widgets\OrderStatsOverviewWidget;
use App\Models\Order;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class ManageOrders extends ManageRecords
{
    protected static string $resource = OrderResource::class;

    protected ?string $subheading = 'Kelola pesanan marketplace, pantau status pembayaran escrow, dan verifikasi konfirmasi pengiriman kurir.';

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            OrderStatsOverviewWidget::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Pesanan')
                ->icon(Heroicon::OutlinedQueueList)
                ->badge(Order::count())
                ->badgeColor('gray'),

            'pending_payment' => Tab::make('Menunggu Bayar')
                ->icon(Heroicon::OutlinedClock)
                ->badge(Order::where('status', 'like', '%pending_payment%')->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'like', '%pending_payment%')),

            'payment_verification' => Tab::make('Verifikasi Bayar')
                ->icon(Heroicon::OutlinedDocumentMagnifyingGlass)
                ->badge(Order::where('status', 'like', '%payment_verification%')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'like', '%payment_verification%')),
            'processing' => Tab::make('Perlu Diproses')
                ->icon(Heroicon::OutlinedArrowPath)
                ->badge(Order::where('status', 'like', '%processing%')->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'like', '%processing%')),

            'shipped' => Tab::make('Sedang Dikirim')
                ->icon(Heroicon::OutlinedTruck)
                ->badge(Order::where('status', 'like', '%shipped%')->count())
                ->badgeColor('primary')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'like', '%shipped%')),

            'delivered' => Tab::make('Selesai')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->badge(Order::where('status', 'like', '%delivered%')->orWhere('status', 'like', '%completed%')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'like', '%delivered%')->orWhere('status', 'like', '%completed%')),
        ];
    }
}
