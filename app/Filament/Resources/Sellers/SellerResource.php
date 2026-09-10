<?php

namespace App\Filament\Resources\Sellers;

use App\Enums\SellerStatus;
use App\Filament\Resources\Sellers\Pages\ManageSellers;
use App\Models\Seller;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use UnitEnum;

class SellerResource extends Resource
{
    protected static ?string $model = Seller::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static string|UnitEnum|null $navigationGroup = 'Mitra Toko (Seller)';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Manajemen Toko Seller';

    protected static ?string $modelLabel = 'Toko Seller';

    protected static ?string $pluralModelLabel = 'Daftar Toko & Seller';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('store_name')
                    ->label('Toko Seller')
                    ->formatStateUsing(function ($state, Seller $record) {
                        $avatar = $record->user?->avatar;
                        $name = e($record->store_name);
                        $username = e($record->username);
                        $initial = strtoupper(substr($record->store_name ?: 'W', 0, 1));
                        $isVerified = $record->status === SellerStatus::VERIFIED;

                        $badgeSvg = $isVerified
                            ? '<svg style="width: 15px; height: 15px; fill: #4F26A6; display: inline-block; margin-left: 4px; vertical-align: -2px;" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>'
                            : '';

                        if (! empty($avatar) && ! str_starts_with($avatar, 'data:')) {
                            $avatarHtml = '<img src="'.e($avatar).'" alt="" style="width: 38px; height: 38px; border-radius: 9999px; object-fit: cover; border: 1.5px solid #E9D5FF; flex-shrink: 0;" />';
                        } else {
                            $avatarHtml = '<div style="width: 38px; height: 38px; border-radius: 9999px; background: #F3EEFF; border: 1.5px solid #E9D5FF; color: #4F26A6; font-weight: 800; font-size: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">'.$initial.'</div>';
                        }

                        return new HtmlString('
                        <div style="display: flex; align-items: center; gap: 10px; padding: 4px 0;">
                            '.$avatarHtml.'
                            <div style="display: flex; flex-direction: column; min-width: 0;">
                                <div style="font-weight: 700; color: #0F172A; font-size: 13.5px; line-height: 1.2; display: flex; align-items: center;">
                                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 155px;">'.$name.'</span>
                                    '.$badgeSvg.'
                                </div>
                                <span style="font-size: 11.5px; color: #6B21A8; font-weight: 600; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 155px;">@'.$username.'</span>
                            </div>
                        </div>');
                    })
                    ->html()
                    ->searchable(['store_name', 'username'])
                    ->sortable()
                    ->url(fn (Seller $record) => url('/seller/@'.$record->username))
                    ->openUrlInNewTab()
                    ->tooltip('Buka etalase toko publik di tab baru'),
                TextColumn::make('products_count')
                    ->counts('products')
                    ->label('Katalog')
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-m-tag')
                    ->formatStateUsing(fn ($state) => $state.' Produk')
                    ->description(fn (Seller $record) => $record->orders()->count().' Pesanan Masuk')
                    ->sortable()
                    ->visibleFrom('sm'),

                TextColumn::make('user.phone')
                    ->label('Kontak Pemilik')
                    ->formatStateUsing(function ($state, Seller $record) {
                        $name = e($record->user?->name ?? 'Pemilik');
                        $email = e($record->user?->email ?? '-');
                        $phone = e($record->user?->phone ?? '-');
                        $rawDigits = $record->user?->phone ? preg_replace('/[^0-9]/', '', $record->user->phone) : null;
                        $waNumber = $rawDigits ? (str_starts_with($rawDigits, '0') ? '62'.substr($rawDigits, 1) : $rawDigits) : null;
                        $waBadge = $waNumber ? '<a href="https://wa.me/'.$waNumber.'" target="_blank" style="display: inline-flex; align-items: center; gap: 3px; padding: 1px 6px; border-radius: 4px; background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; font-size: 10px; font-weight: 700; text-decoration: none; line-height: 1.4;"><svg style="width: 10px; height: 10px; fill: currentColor;" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>WA</a>' : '';

                        return new HtmlString('
                        <div style="display: flex; flex-direction: column; gap: 2px; padding: 4px 0;">
                            <span style="font-size: 12.5px; font-weight: 700; color: #1E293B;">'.$name.'</span>
                            <span style="font-size: 11px; color: #64748B; font-family: ui-monospace, monospace;">'.$email.'</span>
                            <div style="display: flex; align-items: center; gap: 6px; margin-top: 2px;">
                                <span style="font-size: 11.5px; font-weight: 700; color: #0F172A; font-family: ui-monospace, monospace;">'.$phone.'</span>
                                '.$waBadge.'
                            </div>
                        </div>');
                    })
                    ->html()
                    ->searchable(['user.name', 'user.email', 'user.phone'])
                    ->visibleFrom('md'),

                TextColumn::make('bank_name')
                    ->label('Rekening Payout')
                    ->formatStateUsing(function ($state, Seller $record) {
                        $bank = strtoupper(e($record->bank_name));
                        $accNo = e($record->bank_account_number);
                        $accName = e($record->bank_account_name);

                        return new HtmlString('
                        <div style="display: flex; flex-direction: column; gap: 2px; padding: 4px 0;">
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span style="padding: 1px 6px; border-radius: 4px; background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; font-size: 10px; font-weight: 800; letter-spacing: 0.05em;">'.$bank.'</span>
                                <span style="font-family: ui-monospace, monospace; font-size: 12px; font-weight: 700; color: #0F172A;">'.$accNo.'</span>
                            </div>
                            <span style="font-size: 11px; color: #64748B;">a/n '.$accName.'</span>
                        </div>');
                    })
                    ->html()
                    ->searchable(['bank_name', 'bank_account_number', 'bank_account_name'])
                    ->visibleFrom('lg'),

                TextColumn::make('status')
                    ->label('Status Toko')
                    ->badge()
                    ->formatStateUsing(fn (SellerStatus $state): string => match ($state) {
                        SellerStatus::VERIFIED => 'Aktif (Live)',
                        SellerStatus::PENDING => 'Menunggu Moderasi',
                        SellerStatus::REJECTED => 'Ditolak',
                        SellerStatus::SUSPENDED => 'Nonaktif',
                    })
                    ->color(fn (SellerStatus $state): string => match ($state) {
                        SellerStatus::VERIFIED => 'success',
                        SellerStatus::PENDING => 'warning',
                        SellerStatus::REJECTED => 'danger',
                        SellerStatus::SUSPENDED => 'gray',
                    })
                    ->icon(fn (SellerStatus $state): string => match ($state) {
                        SellerStatus::VERIFIED => 'heroicon-m-check-badge',
                        SellerStatus::PENDING => 'heroicon-m-clock',
                        SellerStatus::REJECTED => 'heroicon-m-x-circle',
                        SellerStatus::SUSPENDED => 'heroicon-m-no-symbol',
                    })
                    ->description(fn (Seller $record) => $record->status === SellerStatus::SUSPENDED && $record->rejection_reason ? $record->rejection_reason : ($record->verified_at ? 'Sejak '.$record->verified_at->translatedFormat('d M Y') : 'Daftar '.$record->created_at->diffForHumans()))
                    ->visibleFrom('sm'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Toko')
                    ->options([
                        'verified' => 'Aktif (Live)',
                        'suspended' => 'Nonaktif',
                        'pending' => 'Menunggu Moderasi',
                        'rejected' => 'Ditolak',
                    ]),
                SelectFilter::make('bank_name')
                    ->label('Bank Payout')
                    ->options(fn () => Seller::query()->distinct()->pluck('bank_name', 'bank_name')->toArray()),
            ])
            ->recordActions([
                Action::make('view_details')
                    ->label('Detail Toko')
                    ->tooltip('Rincian Toko & Kontak')
                    ->icon('heroicon-o-eye')
                    ->iconButton()
                    ->color('primary')
                    ->modalHeading(fn (Seller $record) => 'Rincian Toko & Kontak - '.$record->store_name)
                    ->modalWidth('xl')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(fn (Seller $record) => view('filament.modals.seller-details', ['seller' => $record->loadMissing('user')])),

                ActionGroup::make([
                    Action::make('open_store')
                        ->label('Kunjungi Toko Publik')
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->color('gray')
                        ->url(fn (Seller $record) => url('/seller/@'.$record->username))
                        ->openUrlInNewTab(),

                    Action::make('toggle_status')
                        ->label(fn (Seller $record): string => $record->status === SellerStatus::VERIFIED ? 'Nonaktifkan Toko' : 'Aktifkan Toko')
                        ->icon(fn (Seller $record): string => $record->status === SellerStatus::VERIFIED ? 'heroicon-o-no-symbol' : 'heroicon-o-check-circle')
                        ->color(fn (Seller $record): string => $record->status === SellerStatus::VERIFIED ? 'warning' : 'success')
                        ->requiresConfirmation()
                        ->modalHeading(fn (Seller $record): string => $record->status === SellerStatus::VERIFIED ? "Nonaktifkan Toko '{$record->store_name}'?" : "Aktifkan Toko '{$record->store_name}'?")
                        ->modalDescription(fn (Seller $record): string => $record->status === SellerStatus::VERIFIED
                            ? 'Toko ini akan dinonaktifkan. Produk tidak dapat dibeli di storefront, etalase publik akan menampilkan status nonaktif, dan seller akan mendapatkan pemberitahuan di dashboard.'
                            : 'Toko ini akan diaktifkan kembali. Produk toko dapat dibeli kembali oleh pembeli dan seller dapat berjualan secara normal.')
                        ->modalSubmitActionLabel(fn (Seller $record): string => $record->status === SellerStatus::VERIFIED ? 'Ya, Nonaktifkan Toko' : 'Ya, Aktifkan Toko')
                        ->form(fn (Seller $record): array => $record->status === SellerStatus::VERIFIED ? [
                            Textarea::make('reason')
                                ->label('Alasan Penonaktifan (Opsional)')
                                ->placeholder('Contoh: Evaluasi kepatuhan toko, toko libur sementara, atau pelanggaran ketentuan')
                                ->rows(3),
                        ] : [])
                        ->action(function (Seller $record, array $data) {
                            if ($record->status === SellerStatus::VERIFIED) {
                                $record->update([
                                    'status' => SellerStatus::SUSPENDED,
                                    'rejection_reason' => ! empty($data['reason']) ? $data['reason'] : 'Toko dinonaktifkan oleh administrator WhiMarket.',
                                ]);
                                Notification::make()
                                    ->title('Toko Berhasil Dinonaktifkan')
                                    ->body("Toko '{$record->store_name}' kini berstatus nonaktif.")
                                    ->warning()
                                    ->send();
                            } else {
                                $record->update([
                                    'status' => SellerStatus::VERIFIED,
                                    'verified_at' => $record->verified_at ?? now(),
                                    'rejection_reason' => null,
                                ]);
                                Notification::make()
                                    ->title('Toko Berhasil Diaktifkan!')
                                    ->body("Toko '{$record->store_name}' kini berstatus aktif terverifikasi.")
                                    ->success()
                                    ->send();
                            }
                        }),
                    Action::make('reject')
                        ->label('Tolak Toko')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (Seller $record): bool => $record->status === SellerStatus::PENDING)
                        ->form([
                            Textarea::make('rejection_reason')->label('Alasan Penolakan')->required(),
                        ])
                        ->action(function (Seller $record, array $data) {
                            $record->update([
                                'status' => SellerStatus::REJECTED,
                                'rejection_reason' => $data['rejection_reason'],
                            ]);
                            Notification::make()->title('Toko Ditolak')->danger()->send();
                        }),

                    DeleteAction::make()->label('Hapus Toko'),
                ])
                    ->iconButton()
                    ->tooltip('Opsi Menu Toko'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSellers::route('/'),
        ];
    }
}
