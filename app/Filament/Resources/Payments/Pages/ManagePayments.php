<?php

namespace App\Filament\Resources\Payments\Pages;

use App\Enums\PaymentStatus;
use App\Filament\Resources\Payments\PaymentResource;
use App\Filament\Resources\Payments\Widgets\PaymentStatsOverviewWidget;
use App\Models\Payment;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class ManagePayments extends ManageRecords
{
    protected static string $resource = PaymentResource::class;

    protected ?string $subheading = 'Verifikasi mutasi bukti transfer rekening escrow bersama marketplace, validasi setoran pembeli, dan aktifkan proses pesanan toko.';

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PaymentStatsOverviewWidget::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Pembayaran')
                ->icon(Heroicon::OutlinedQueueList)
                ->badge(Payment::count())
                ->badgeColor('gray'),

            'pending_review' => Tab::make('Perlu Verifikasi')
                ->icon(Heroicon::OutlinedClock)
                ->badge(Payment::where('status', PaymentStatus::PENDING_REVIEW)->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn ($query) => $query->where('status', PaymentStatus::PENDING_REVIEW)),

            'verified' => Tab::make('Terverifikasi Sah')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->badge(Payment::where('status', PaymentStatus::VERIFIED)->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn ($query) => $query->where('status', PaymentStatus::VERIFIED)),

            'rejected' => Tab::make('Bukti Ditolak')
                ->icon(Heroicon::OutlinedXCircle)
                ->badge(Payment::where('status', PaymentStatus::REJECTED)->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn ($query) => $query->where('status', PaymentStatus::REJECTED)),

            'unpaid' => Tab::make('Menunggu Transfer')
                ->icon(Heroicon::OutlinedBanknotes)
                ->badge(Payment::where('status', PaymentStatus::UNPAID)->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn ($query) => $query->where('status', PaymentStatus::UNPAID)),
        ];
    }
}
