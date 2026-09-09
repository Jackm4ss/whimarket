<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AdminHeroWidget;
use App\Filament\Widgets\EscrowMetricWidget;
use App\Filament\Widgets\OrderStatusBreakdownWidget;
use App\Filament\Widgets\RecentOrdersWidget;
use App\Filament\Widgets\RevenueMetricWidget;
use App\Filament\Widgets\SalesGoalWidget;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Livewire;
use Filament\Support\Icons\Heroicon;

class Dashboard extends BaseDashboard
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = -1;

    public function getColumns(): int|array
    {
        return [
            'md' => 2,
            'xl' => 3,
        ];
    }

    public function getWidgetsContentComponent(): Component
    {
        return Grid::make($this->getColumns())
            ->schema([
                Livewire::make(AdminHeroWidget::class)
                    ->columnSpanFull(),

                Livewire::make(RevenueMetricWidget::class)
                    ->columnSpan(1),

                Livewire::make(EscrowMetricWidget::class)
                    ->columnSpan(1),

                Livewire::make(SalesGoalWidget::class)
                    ->columnSpan(1),

                Livewire::make(OrderStatusBreakdownWidget::class)
                    ->columnSpan([
                        'md' => 1,
                        'xl' => 1,
                    ]),

                Livewire::make(RecentOrdersWidget::class)
                    ->columnSpan([
                        'md' => 1,
                        'xl' => 2,
                    ]),
            ]);
    }
}
