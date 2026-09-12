<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use LaBoiteACode\FilamentDashboardWidgets\Data\RecentItem;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\RecentItemsWidget;

class RecentOrdersWidget extends RecentItemsWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 6;

    protected ?string $heading = 'Pesanan Terbaru';

    protected int|string|array $columnSpan = [
        'md' => 6,
        'xl' => 6,
    ];

    protected ?string $emptyStateHeading = 'Belum ada pesanan';

    protected ?string $emptyStateDescription = 'Pesanan terbaru akan tampil di sini.';

    protected function getViewAllUrl(): ?string
    {
        return OrderResource::getUrl('index');
    }

    protected function getItems(): array
    {
        $orders = Order::with('buyer')->latest()->limit(5)->get();

        return $orders->map(fn (Order $order) => RecentItem::make(
            title: "#{$order->order_number}",
            description: 'Pembeli: '.($order->buyer->name ?? 'Buyer'),
        )
            ->meta('Rp '.number_format((float) $order->total_amount, 0, ',', '.'))
            ->badge((string) $order->status)
            ->badgeColor('primary')
            ->url(OrderResource::getUrl('index')))
            ->all();
    }
}
