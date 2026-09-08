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

    protected function getViewAllUrl(): ?string
    {
        return OrderResource::getUrl('index');
    }

    protected function getItems(): array
    {
        $orders = Order::with('buyer')->latest()->limit(5)->get();

        if ($orders->isEmpty()) {
            return [
                RecentItem::make(
                    title: '#WHI-ORD-2026-001',
                    description: 'Pembeli: Budi Pratama',
                )
                    ->meta('Rp 450.000')
                    ->badge('Processing')
                    ->badgeColor('primary'),
                RecentItem::make(
                    title: '#WHI-ORD-2026-002',
                    description: 'Pembeli: Siti Rahma',
                )
                    ->meta('Rp 1.200.000')
                    ->badge('Delivered')
                    ->badgeColor('success'),
            ];
        }

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
