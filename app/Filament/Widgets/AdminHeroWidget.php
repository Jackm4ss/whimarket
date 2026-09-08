<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class AdminHeroWidget extends Widget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.admin-hero';
}
