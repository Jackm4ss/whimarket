<?php

namespace App\Filament\Resources\Disputes\Pages;

use App\Filament\Resources\Disputes\DisputeResource;
use App\Filament\Resources\Disputes\Widgets\DisputeStatsOverviewWidget;
use App\Models\Dispute;
use App\States\Dispute\ResolvedRefund;
use App\States\Dispute\ResolvedRejected;
use App\States\Dispute\SellerResponded;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class ManageDisputes extends ManageRecords
{
    protected static string $resource = DisputeResource::class;

    protected ?string $subheading = 'Pusat mediasi sengketa & komplain barang pembeli. Periksa bukti foto unboxing, tanggapan seller, dan tentukan keputusan pengembalian dana (refund) atau pencairan ke penjual.';

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            DisputeStatsOverviewWidget::class,
        ];
    }

    public function getTabs(): array
    {
        $allCount = Dispute::count();
        $activeCount = Dispute::whereNotState('status', [ResolvedRefund::class, ResolvedRejected::class])->count();
        $sellerRespondedCount = Dispute::whereState('status', SellerResponded::class)->count();
        $refundedCount = Dispute::whereState('status', ResolvedRefund::class)->count();
        $rejectedCount = Dispute::whereState('status', ResolvedRejected::class)->count();

        return [
            'all' => Tab::make('Semua Sengketa')
                ->icon(Heroicon::OutlinedScale)
                ->badge($allCount)
                ->badgeColor('gray'),

            'active' => Tab::make('Perlu Mediasi (Aktif)')
                ->icon(Heroicon::OutlinedExclamationTriangle)
                ->badge($activeCount)
                ->badgeColor('danger')
                ->modifyQueryUsing(fn ($query) => $query->whereNotState('status', [ResolvedRefund::class, ResolvedRejected::class])),

            'seller_responded' => Tab::make('Seller Merespon')
                ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                ->badge($sellerRespondedCount)
                ->badgeColor('warning')
                ->modifyQueryUsing(fn ($query) => $query->whereState('status', SellerResponded::class)),

            'refunded' => Tab::make('Refund Pembeli')
                ->icon(Heroicon::OutlinedArrowPathRoundedSquare)
                ->badge($refundedCount)
                ->badgeColor('info')
                ->modifyQueryUsing(fn ($query) => $query->whereState('status', ResolvedRefund::class)),

            'rejected' => Tab::make('Cairkan ke Seller')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->badge($rejectedCount)
                ->badgeColor('success')
                ->modifyQueryUsing(fn ($query) => $query->whereState('status', ResolvedRejected::class)),
        ];
    }
}
