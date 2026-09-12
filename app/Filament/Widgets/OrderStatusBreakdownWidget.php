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

    protected ?string $emptyStateHeading = 'Belum ada pesanan';

    protected ?string $emptyStateDescription = 'Distribusi status akan muncul setelah ada pesanan masuk.';

    protected function getItems(): array
    {
        $delivered = Order::where('status', 'like', '%delivered%')->orWhere('status', 'like', '%completed%')->count();
        $shipped = Order::where('status', 'like', '%shipped%')->count();
        $processing = Order::where('status', 'like', '%processing%')->orWhere('status', 'like', '%paid%')->count();
        $dispute = Order::where('status', 'like', '%disputed%')->count();

        if (($delivered + $shipped + $processing + $dispute) === 0) {
            return [];
        }

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
