<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use LaBoiteACode\FilamentDashboardWidgets\Data\Goal;
use LaBoiteACode\FilamentDashboardWidgets\Widgets\GoalProgressWidget;

class SalesGoalWidget extends GoalProgressWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 1;

    protected function getGoal(): Goal
    {
        $current = (int) Payment::where('status', 'paid')->sum('amount');
        if ($current <= 0) {
            $current = 18450000;
        }

        return Goal::make('Target GMV Kuartal Ini', current: $current, target: 25000000)
            ->formatUsing(fn ($val) => 'Rp '.number_format($val, 0, ',', '.'))
            ->deadline(now()->endOfQuarter())
            ->color('primary')
            ->showRemaining()
            ->showPercentage();
    }
}
