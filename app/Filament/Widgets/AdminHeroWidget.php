<?php

namespace App\Filament\Widgets;

use App\Models\Dispute;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Payout;
use App\Models\Product;
use App\Models\Seller;
use Filament\Widgets\Widget;

class AdminHeroWidget extends Widget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.admin-hero';

    protected function getViewData(): array
    {
        return [
            'pendingOrdersCount' => Order::where('status', 'like', '%processing%')->orWhere('status', 'like', '%payment_verification%')->count(),
            'pendingPaymentsCount' => Payment::where('status', 'pending_review')->count(),
            'pendingDisputesCount' => Dispute::whereIn('status', ['open', 'seller_responded', 'under_admin_review'])->count(),
            'pendingPayoutsCount' => Payout::where('status', 'pending')->count(),
            'totalSellersCount' => Seller::count(),
            'totalProductsCount' => Product::count(),
        ];
    }
}
