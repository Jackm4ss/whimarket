<?php

namespace App\Filament\Resources\Payouts\Pages;

use App\Enums\PayoutStatus;
use App\Filament\Resources\Payouts\PayoutResource;
use App\Filament\Resources\Payouts\Widgets\PayoutStatsOverviewWidget;
use App\Models\Payout;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class ManagePayouts extends ManageRecords
{
    protected static string $resource = PayoutResource::class;

    protected ?string $subheading = 'Kelola pencairan dana hasil penjualan escrow ke rekening bank seller. Verifikasi rekening tujuan dan unggah bukti transfer bank.';

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PayoutStatsOverviewWidget::class,
        ];
    }

    public function getTabs(): array
    {
        $allCount = Payout::count();
        $pendingCount = Payout::where('status', PayoutStatus::PENDING)->count();
        $paidCount = Payout::where('status', PayoutStatus::PAID)->count();
        $failedCount = Payout::where('status', PayoutStatus::FAILED)->count();

        return [
            'all' => Tab::make('Semua Payout')
                ->icon(Heroicon::OutlinedBanknotes)
                ->badge($allCount)
                ->badgeColor('gray'),

            'pending' => Tab::make('Menunggu Transfer')
                ->icon(Heroicon::OutlinedClock)
                ->badge($pendingCount)
                ->badgeColor('warning')
                ->modifyQueryUsing(fn ($query) => $query->where('status', PayoutStatus::PENDING)),

            'paid' => Tab::make('Telah Dicairkan')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->badge($paidCount)
                ->badgeColor('success')
                ->modifyQueryUsing(fn ($query) => $query->where('status', PayoutStatus::PAID)),

            'failed' => Tab::make('Gagal')
                ->icon(Heroicon::OutlinedXCircle)
                ->badge($failedCount)
                ->badgeColor('danger')
                ->modifyQueryUsing(fn ($query) => $query->where('status', PayoutStatus::FAILED)),
        ];
    }
}
