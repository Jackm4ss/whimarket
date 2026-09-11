<?php

namespace App\Filament\Resources\SellerAccessCodes\Pages;

use App\Filament\Resources\SellerAccessCodes\SellerAccessCodeResource;
use App\Filament\Resources\SellerAccessCodes\Widgets\SellerAccessCodeStatsOverviewWidget;
use App\Models\SellerAccessCode;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class ListSellerAccessCodes extends ListRecords
{
    protected static string $resource = SellerAccessCodeResource::class;

    protected ?string $subheading = 'Kelola kode registrasi eksklusif untuk onboarding seller VIP & content creator WhiMarket. Pantau kuota pemakaian, batasi email tertentu, dan atur proteksi akses.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Generate Kode VIP Baru')
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SellerAccessCodeStatsOverviewWidget::class,
        ];
    }

    public function getTabs(): array
    {
        $allCount = SellerAccessCode::count();

        $activeCount = SellerAccessCode::where('is_locked', false)
            ->where(function ($query) {
                $query->whereNull('max_uses')
                    ->orWhereColumn('used_count', '<', 'max_uses');
            })
            ->count();

        $lockedCount = SellerAccessCode::where('is_locked', true)->count();

        $exhaustedCount = SellerAccessCode::where('is_locked', false)
            ->whereNotNull('max_uses')
            ->whereColumn('used_count', '>=', 'max_uses')
            ->count();

        $emailCount = SellerAccessCode::whereNotNull('email')
            ->where('email', '!=', '')
            ->count();

        return [
            'all' => Tab::make('Semua Kode')
                ->icon(Heroicon::OutlinedKey)
                ->badge($allCount)
                ->badgeColor('gray'),

            'active' => Tab::make('Aktif (Siap Pakai)')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->badge($activeCount)
                ->badgeColor('success')
                ->modifyQueryUsing(fn ($query) => $query->where('is_locked', false)
                    ->where(function ($q) {
                        $q->whereNull('max_uses')
                            ->orWhereColumn('used_count', '<', 'max_uses');
                    })
                ),

            'locked' => Tab::make('Terkunci')
                ->icon(Heroicon::OutlinedLockClosed)
                ->badge($lockedCount)
                ->badgeColor('danger')
                ->modifyQueryUsing(fn ($query) => $query->where('is_locked', true)),

            'exhausted' => Tab::make('Kuota Habis')
                ->icon(Heroicon::OutlinedXCircle)
                ->badge($exhaustedCount)
                ->badgeColor('warning')
                ->modifyQueryUsing(fn ($query) => $query->where('is_locked', false)
                    ->whereNotNull('max_uses')
                    ->whereColumn('used_count', '>=', 'max_uses')
                ),

            'email_bound' => Tab::make('Khusus Email')
                ->icon(Heroicon::OutlinedEnvelope)
                ->badge($emailCount)
                ->badgeColor('info')
                ->modifyQueryUsing(fn ($query) => $query->whereNotNull('email')->where('email', '!=', '')),
        ];
    }
}
