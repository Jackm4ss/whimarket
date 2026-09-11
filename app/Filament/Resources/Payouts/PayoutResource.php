<?php

namespace App\Filament\Resources\Payouts;

use App\Enums\PayoutStatus;
use App\Filament\Resources\Payouts\Pages\ManagePayouts;
use App\Models\Payout;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class PayoutResource extends Resource
{
    protected static ?string $model = Payout::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|UnitEnum|null $navigationGroup = 'Keuangan & Sengketa';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Pencairan Dana (Payout)';

    protected static ?string $modelLabel = 'Pencairan Dana';

    protected static ?string $pluralModelLabel = 'Daftar Pencairan Saldo Seller';

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->recordActionsColumnLabel('Aksi')
            ->recordActionsAlignment('center')
            ->columns([
                TextColumn::make('seller.store_name')
                    ->label('Toko Seller')
                    ->icon('heroicon-m-building-storefront')
                    ->weight('bold')
                    ->color('primary')
                    ->description(fn (Payout $record) => $record->seller ? '@'.$record->seller->username : '-')
                    ->url(fn (Payout $record) => $record->seller ? url('/seller/@'.$record->seller->username) : null)
                    ->openUrlInNewTab()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('order.order_number')
                    ->label('No. Pesanan')
                    ->icon('heroicon-m-shopping-bag')
                    ->fontFamily('mono')
                    ->weight('medium')
                    ->copyable()
                    ->copyMessage('Nomor pesanan berhasil disalin!')
                    ->description(fn (Payout $record) => 'Total: Rp '.number_format((float) ($record->order?->grand_total ?? 0), 0, ',', '.'))
                    ->searchable(),

                TextColumn::make('amount')
                    ->label('Nominal Payout')
                    ->formatStateUsing(fn ($state) => 'Rp '.number_format((float) $state, 0, ',', '.'))
                    ->weight('bold')
                    ->color('success')
                    ->description('Dana Bersih Seller')
                    ->sortable(),

                TextColumn::make('bank_details')
                    ->label('Rekening Tujuan')
                    ->icon('heroicon-m-credit-card')
                    ->weight('medium')
                    ->getStateUsing(function (Payout $record): string {
                        $bank = $record->bank_details_snapshot;
                        if (! is_array($bank) || empty($bank['account_number'])) {
                            return $record->seller?->bank_name ? "{$record->seller->bank_name} - {$record->seller->bank_account_number}" : 'Belum Diatur';
                        }

                        return ($bank['bank_name'] ?? 'Bank').' - '.($bank['account_number'] ?? '-');
                    })
                    ->description(function (Payout $record): string {
                        $bank = $record->bank_details_snapshot;
                        $name = is_array($bank) && ! empty($bank['account_name']) ? $bank['account_name'] : ($record->seller?->bank_account_name ?? '-');

                        return "a/n {$name}";
                    }),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof PayoutStatus ? $state->label() : (is_string($state) ? ucfirst($state) : '-'))
                    ->icon(fn ($state): string => match ($state instanceof PayoutStatus ? $state : PayoutStatus::tryFrom((string) $state)) {
                        PayoutStatus::PENDING => 'heroicon-m-clock',
                        PayoutStatus::PROCESSING => 'heroicon-m-arrow-path',
                        PayoutStatus::PAID => 'heroicon-m-check-badge',
                        PayoutStatus::FAILED => 'heroicon-m-x-circle',
                        default => 'heroicon-m-ellipsis-horizontal',
                    })
                    ->color(fn ($state): string => match ($state instanceof PayoutStatus ? $state : PayoutStatus::tryFrom((string) $state)) {
                        PayoutStatus::PENDING => 'warning',
                        PayoutStatus::PROCESSING => 'info',
                        PayoutStatus::PAID => 'success',
                        PayoutStatus::FAILED => 'danger',
                        default => 'gray',
                    }),

                ImageColumn::make('transfer_proof_path')
                    ->label('Bukti Transfer')
                    ->disk('public')
                    ->square()
                    ->size(36)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Tanggal Selesai')
                    ->dateTime('d M Y')
                    ->description(fn (Payout $record) => $record->processed_at ? 'Ditransfer: '.$record->processed_at->diffForHumans() : $record->created_at?->diffForHumans())
                    ->sortable(),
            ])
            ->emptyStateHeading('Belum Ada Riwayat Payout')
            ->emptyStateDescription('Pencairan dana seller otomatis dibuat ketika pesanan diselesaikan pembeli atau lolos verifikasi inspeksi 48 jam.')
            ->emptyStateIcon('heroicon-o-banknotes')
            ->recordActions([
                Action::make('mark_transferred')
                    ->label('Cairkan Dana')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->iconButton()
                    ->color('success')
                    ->tooltip('Cairkan Dana ke Rekening Seller')
                    ->visible(fn (Payout $record): bool => $record->status === PayoutStatus::PENDING)
                    ->modalHeading(fn (Payout $record) => 'Pencairan Dana ke Rekening Seller - Rp '.number_format((float) $record->amount, 0, ',', '.'))
                    ->modalDescription(function (Payout $record): string {
                        $bank = $record->bank_details_snapshot;
                        $bankName = is_array($bank) ? ($bank['bank_name'] ?? 'Bank') : ($record->seller?->bank_name ?? 'Bank');
                        $accNo = is_array($bank) ? ($bank['account_number'] ?? '-') : ($record->seller?->bank_account_number ?? '-');
                        $accName = is_array($bank) ? ($bank['account_name'] ?? '-') : ($record->seller?->bank_account_name ?? '-');

                        return 'Pastikan Anda telah mentransfer dana sebesar Rp '.number_format((float) $record->amount, 0, ',', '.')." ke rekening {$bankName} - {$accNo} (a/n {$accName}).";
                    })
                    ->form([
                        FileUpload::make('transfer_proof')
                            ->label('Screenshot Bukti Transfer Bank')
                            ->disk('public')
                            ->directory('payouts/proofs')
                            ->image()
                            ->required()
                            ->helperText('Format JPG/PNG/WebP, maksimal 5MB.'),
                    ])
                    ->action(function (Payout $record, array $data) {
                        $record->update([
                            'status' => PayoutStatus::PAID,
                            'transfer_proof_path' => $data['transfer_proof'],
                            'processed_by' => Auth::id(),
                            'processed_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Dana Rp '.number_format((float) $record->amount, 0, ',', '.').' Berhasil Dicairkan ke Seller!')
                            ->success()
                            ->send();
                    }),

                ActionGroup::make([
                    Action::make('view_proof')
                        ->label('Lihat Bukti Transfer')
                        ->icon('heroicon-o-photo')
                        ->url(fn (Payout $record) => asset('storage/'.$record->transfer_proof_path))
                        ->openUrlInNewTab()
                        ->visible(fn (Payout $record) => filled($record->transfer_proof_path)),

                    Action::make('view_order')
                        ->label('Lihat Daftar Pesanan')
                        ->icon('heroicon-o-shopping-bag')
                        ->url(fn (Payout $record) => $record->order ? url('/admin/orders') : null)
                        ->visible(fn (Payout $record) => (bool) $record->order_id),

                    Action::make('view_seller')
                        ->label('Kunjungi Toko Seller')
                        ->icon('heroicon-o-building-storefront')
                        ->url(fn (Payout $record) => $record->seller ? url('/seller/@'.$record->seller->username) : null)
                        ->openUrlInNewTab()
                        ->visible(fn (Payout $record) => (bool) $record->seller_id),

                    DeleteAction::make()
                        ->label('Hapus Payout')
                        ->icon('heroicon-o-trash'),
                ])
                    ->iconButton()
                    ->color('gray')
                    ->tooltip('Opsi Menu Payout'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePayouts::route('/'),
        ];
    }
}
