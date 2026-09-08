<?php

namespace App\Filament\Resources\Payments;

use App\Enums\PaymentStatus;
use App\Filament\Resources\Payments\Pages\ManagePayments;
use App\Models\Payment;
use App\States\Order\Paid;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static ?string $navigationLabel = 'Verifikasi Pembayaran';

    protected static ?string $modelLabel = 'Pembayaran';

    protected static ?string $pluralModelLabel = 'Verifikasi Pembayaran';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order.order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('bank_destination')
                    ->label('Bank Tujuan'),
                TextColumn::make('sender_bank_name')
                    ->label('Bank Pengirim')
                    ->placeholder('-'),
                TextColumn::make('sender_account_name')
                    ->label('Nama Pengirim')
                    ->placeholder('-'),
                TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (PaymentStatus $state): string => match ($state) {
                        PaymentStatus::UNPAID => 'gray',
                        PaymentStatus::PENDING_REVIEW => 'warning',
                        PaymentStatus::VERIFIED => 'success',
                        PaymentStatus::REJECTED => 'danger',
                    }),
                ImageColumn::make('proof_path')
                    ->label('Bukti Transfer')
                    ->disk('public'),
                TextColumn::make('created_at')
                    ->label('Waktu Upload')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Setujui (Valid)')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi Pembayaran Valid')
                    ->modalDescription('Apakah Anda yakin transfer pembayaran ini valid dan dana telah masuk ke rekening WhiMarket?')
                    ->visible(fn (Payment $record): bool => in_array($record->status, [PaymentStatus::PENDING_REVIEW, PaymentStatus::UNPAID]))
                    ->action(function (Payment $record) {
                        $record->update([
                            'status' => PaymentStatus::VERIFIED,
                            'verified_by' => Auth::id(),
                            'verified_at' => now(),
                        ]);

                        if ($record->order && $record->order->status->canTransitionTo(Paid::class)) {
                            $record->order->status->transitionTo(Paid::class);
                        }

                        Notification::make()
                            ->title('Pembayaran Berhasil Disetujui!')
                            ->success()
                            ->send();
                    }),

                Action::make('reject')
                    ->label('Tolak Bukti')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Payment $record): bool => $record->status === PaymentStatus::PENDING_REVIEW)
                    ->form([
                        Textarea::make('rejection_reason')
                            ->label('Alasan Penolakan Bukti')
                            ->placeholder('Contoh: Mutasi rekening tidak ditemukan atau nominal transfer kurang.')
                            ->required(),
                    ])
                    ->action(function (Payment $record, array $data) {
                        $record->update([
                            'status' => PaymentStatus::REJECTED,
                            'rejection_reason' => $data['rejection_reason'],
                        ]);

                        Notification::make()
                            ->title('Bukti Pembayaran Ditolak')
                            ->danger()
                            ->send();
                    }),

                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePayments::route('/'),
        ];
    }
}
