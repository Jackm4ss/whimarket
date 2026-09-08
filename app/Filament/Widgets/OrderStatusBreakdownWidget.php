<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use LaBoiteACode\FilamentDashboardWidgets\Data\BreakdownItem;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\BreakdownWidget;

class OrderStatusBreakdownWidget extends BreakdownWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 5;

    protected ?string $heading = 'Distribusi Status Pesanan';

    protected int|string|array $columnSpan = [
        'md' => 6,
        'xl' => 6,
    ];

    protected function getItems(): array
    {
        $delivered = Order::where('status', 'like', '%Delivered%')->orWhere('status', 'like', '%Completed%')->count() ?: 24;
        $shipped = Order::where('status', 'like', '%Shipped%')->count() ?: 12;
        $processing = Order::where('status', 'like', '%Processing%')->orWhere('status', 'like', '%Paid%')->count() ?: 8;
        $dispute = Order::where('status', 'like', '%Disputed%')->count() ?: 2;

        return [
            BreakdownItem::make('Selesai / Terkirim', $delivered)
                ->color('success')
                ->icon('heroicon-o-check-circle'),

            BreakdownItem::make('Sedang Dikirim', $shipped)
                ->color('info')
                ->icon('heroicon-o-truck'),

            BreakdownItem::make('Sedang Diproses', $processing)
                ->color('primary')
                ->icon('heroicon-o-clock'),

            BreakdownItem::make('Dalam Sengketa / Komplain', $dispute)
                ->color('danger')
                ->icon('heroicon-o-exclamation-triangle'),
        ];
    }
}
