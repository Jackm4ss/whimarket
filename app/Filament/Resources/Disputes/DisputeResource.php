<?php

namespace App\Filament\Resources\Disputes;

use App\Enums\PayoutStatus;
use App\Filament\Resources\Disputes\Pages\ManageDisputes;
use App\Models\Dispute;
use App\Models\EscrowBalance;
use App\Models\Payout;
use App\States\Dispute\ResolvedRefund;
use App\States\Dispute\ResolvedRejected;
use App\States\Order\Cancelled;
use App\States\Order\Completed;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class DisputeResource extends Resource
{
    protected static ?string $model = Dispute::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedScale;

    protected static ?string $navigationLabel = 'Pusat Mediasi Sengketa';

    protected static ?string $modelLabel = 'Sengketa';

    protected static ?string $pluralModelLabel = 'Pusat Sengketa & Dispute';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order.order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('buyer.name')
                    ->label('Pembeli')
                    ->searchable(),
                TextColumn::make('order.seller.store_name')
                    ->label('Toko Penjual'),
                TextColumn::make('reason')
                    ->label('Alasan Komplain'),
                TextColumn::make('status')
                    ->label('Status Sengketa')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'danger',
                        'seller_responded' => 'warning',
                        'under_admin_review' => 'info',
                        'resolved_refund' => 'success',
                        'resolved_rejected' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Diajukan')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('approve_refund')
                    ->label('Putuskan: Refund Buyer')
                    ->icon('heroicon-o-check-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Kabulkan Komplain Buyer (Refund)')
                    ->modalDescription('Apakah Anda yakin ingin memenangkan komplain buyer dan memproses refund manual ke rekening pembeli?')
                    ->form([
                        Textarea::make('resolution_notes')
                            ->label('Catatan Keputusan Admin')
                            ->required(),
                    ])
                    ->action(function (Dispute $record, array $data) {
                        $record->update([
                            'status' => ResolvedRefund::class,
                            'resolution_notes' => $data['resolution_notes'],
                            'resolved_by' => Auth::id(),
                            'resolved_at' => now(),
                        ]);

                        if ($record->order && $record->order->status->canTransitionTo(Cancelled::class)) {
                            $record->order->status->transitionTo(Cancelled::class);
                        }

                        Notification::make()
                            ->title('Dispute Dimenangkan Buyer (Refund Disetujui)')
                            ->success()
                            ->send();
                    }),

                Action::make('reject_dispute')
                    ->label('Putuskan: Cairkan ke Seller')
                    ->icon('heroicon-o-shield-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Komplain Buyer (Cairkan ke Seller)')
                    ->modalDescription('Apakah Anda yakin bukti seller valid dan dana transaksi akan dicairkan ke penjual?')
                    ->form([
                        Textarea::make('resolution_notes')
                            ->label('Catatan Keputusan Admin')
                            ->required(),
                    ])
                    ->action(function (Dispute $record, array $data) {
                        $record->update([
                            'status' => ResolvedRejected::class,
                            'resolution_notes' => $data['resolution_notes'],
                            'resolved_by' => Auth::id(),
                            'resolved_at' => now(),
                        ]);

                        if ($record->order && $record->order->status->canTransitionTo(Completed::class)) {
                            $record->order->status->transitionTo(Completed::class);
                            $record->order->update(['completed_at' => now()]);

                            // Release escrow & create payout
                            $escrow = EscrowBalance::where('order_id', $record->order->id)->first();
                            if ($escrow) {
                                $escrow->update(['is_released' => true, 'released_at' => now()]);
                            }

                            Payout::firstOrCreate(
                                ['order_id' => $record->order->id],
                                [
                                    'seller_id' => $record->order->seller_id,
                                    'amount' => $record->order->total_amount,
                                    'bank_details_snapshot' => [
                                        'bank_name' => $record->order->seller->bank_name,
                                        'account_number' => $record->order->seller->bank_account_number,
                                        'account_name' => $record->order->seller->bank_account_name,
                                    ],
                                    'status' => PayoutStatus::PENDING,
                                ]
                            );
                        }

                        Notification::make()
                            ->title('Dispute Ditolak (Order Diselesaikan ke Seller)')
                            ->success()
                            ->send();
                    }),

                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageDisputes::route('/'),
        ];
    }
}
