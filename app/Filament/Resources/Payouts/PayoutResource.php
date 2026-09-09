<?php

namespace App\Filament\Resources\Payouts;

use App\Enums\PayoutStatus;
use App\Filament\Resources\Payouts\Pages\ManagePayouts;
use App\Models\Payout;
use BackedEnum;
use Filament\Actions\Action;
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
            ->columns([
                TextColumn::make('seller.store_name')
                    ->label('Toko Seller')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('order.order_number')
                    ->label('No. Pesanan')
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('Nominal Payout')
                    ->money('IDR')
                    ->weight('bold')
                    ->sortable(),
                TextColumn::make('bank_details_snapshot')
                    ->label('Rekening Tujuan')
                    ->formatStateUsing(function ($state): string {
                        if (is_array($state)) {
                            return ($state['bank_name'] ?? '').' - '.($state['account_number'] ?? '').' (a/n '.($state['account_name'] ?? '').')';
                        }

                        return '-';
                    }),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (PayoutStatus $state): string => match ($state) {
                        PayoutStatus::PENDING => 'warning',
                        PayoutStatus::PROCESSING => 'info',
                        PayoutStatus::PAID => 'success',
                        PayoutStatus::FAILED => 'danger',
                    }),
                ImageColumn::make('transfer_proof_path')
                    ->label('Bukti Transfer')
                    ->disk('public'),
                TextColumn::make('created_at')
                    ->label('Tanggal Order Selesai')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('mark_transferred')
                    ->label('Upload Bukti Transfer (Cairkan)')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Payout $record): bool => $record->status === PayoutStatus::PENDING)
                    ->form([
                        FileUpload::make('transfer_proof')
                            ->label('Screenshot Bukti Transfer Bank')
                            ->disk('public')
                            ->directory('payouts/proofs')
                            ->image()
                            ->required(),
                    ])
                    ->action(function (Payout $record, array $data) {
                        $record->update([
                            'status' => PayoutStatus::PAID,
                            'transfer_proof_path' => $data['transfer_proof'],
                            'processed_by' => Auth::id(),
                            'processed_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Dana Berhasil Dicairkan ke Seller!')
                            ->success()
                            ->send();
                    }),

                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePayouts::route('/'),
        ];
    }
}
