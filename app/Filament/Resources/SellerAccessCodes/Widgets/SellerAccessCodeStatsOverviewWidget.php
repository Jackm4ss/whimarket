<?php

namespace App\Filament\Resources\SellerAccessCodes\Widgets;

use App\Models\SellerAccessCode;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SellerAccessCodeStatsOverviewWidget extends BaseWidget
{
    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $totalCodes = SellerAccessCode::count();

        $activeCodes = SellerAccessCode::where('is_locked', false)
            ->where(function ($query) {
                $query->whereNull('max_uses')
                    ->orWhereColumn('used_count', '<', 'max_uses');
            })
            ->count();

        $totalUsedCount = (int) SellerAccessCode::sum('used_count');

        $lockedOrExhausted = SellerAccessCode::where('is_locked', true)
            ->orWhere(function ($query) {
                $query->whereNotNull('max_uses')
                    ->whereColumn('used_count', '>=', 'max_uses');
            })
            ->count();

        return [
            Stat::make('Total Kode Akses VIP', $totalCodes.' Kode')
                ->description('Seluruh kode registrasi dibuat')
                ->descriptionIcon('heroicon-m-key')
                ->color('primary')
                ->chart([2, 4, 3, 5, 4, max(1, $totalCodes)]),

            Stat::make('Siap Pakai (Aktif)', $activeCodes.' Kode')
                ->description('Kuota tersedia untuk seller baru')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->chart([1, 3, 3, 4, 4, max(1, $activeCodes)]),

            Stat::make('Total Toko Terdaftar', $totalUsedCount.' Seller')
                ->description('Akumulasi registrasi via kode VIP')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info')
                ->chart([1, 2, 4, 6, 8, max(1, $totalUsedCount)]),

            Stat::make('Terkunci / Kuota Habis', $lockedOrExhausted.' Kode')
                ->description($lockedOrExhausted > 0 ? 'Perlu tindakan atau reset kuota' : 'Semua kode berfungsi normal')
                ->descriptionIcon($lockedOrExhausted > 0 ? 'heroicon-m-lock-closed' : 'heroicon-m-shield-check')
                ->color($lockedOrExhausted > 0 ? 'warning' : 'gray')
                ->chart([0, 1, 0, 1, max(0, $lockedOrExhausted)]),
        ];
    }
}
