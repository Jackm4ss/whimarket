<?php

namespace App\Filament\Resources\Disputes;

use App\Enums\PayoutStatus;
use App\Filament\Resources\Disputes\Pages\ManageDisputes;
use App\Models\Dispute;
use App\Models\EscrowBalance;
use App\Models\Payout;
use App\States\Dispute\DisputeStatusState;
use App\States\Dispute\ResolvedRefund;
use App\States\Dispute\ResolvedRejected;
use App\States\Order\Cancelled;
use App\States\Order\Completed;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use UnitEnum;

class DisputeResource extends Resource
{
    protected static ?string $model = Dispute::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedScale;

    protected static string|UnitEnum|null $navigationGroup = 'Keuangan & Sengketa';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Pusat Mediasi Sengketa';

    protected static ?string $modelLabel = 'Sengketa';

    protected static ?string $pluralModelLabel = 'Pusat Sengketa & Dispute';

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->recordActionsColumnLabel('Aksi')
            ->recordActionsAlignment('center')
            ->columns([
                TextColumn::make('order.order_number')
                    ->label('Pesanan')
                    ->icon('heroicon-m-shopping-bag')
                    ->fontFamily('mono')
                    ->weight('bold')
                    ->color('primary')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Nomor pesanan disalin!')
                    ->description(fn (Dispute $record) => 'Total: Rp '.number_format((float) ($record->order?->grand_total ?? 0), 0, ',', '.')),

                TextColumn::make('buyer.name')
                    ->label('Pembeli')
                    ->icon('heroicon-m-user')
                    ->weight('medium')
                    ->searchable()
                    ->description(fn (Dispute $record) => $record->buyer?->email ?? '-'),

                TextColumn::make('order.seller.store_name')
                    ->label('Penjual')
                    ->icon('heroicon-m-building-storefront')
                    ->weight('bold')
                    ->color('primary')
                    ->description(fn (Dispute $record) => $record->order?->seller ? '@'.$record->order->seller->username : '-')
                    ->url(fn (Dispute $record) => $record->order?->seller ? url('/seller/@'.$record->order->seller->username) : null)
                    ->openUrlInNewTab()
                    ->searchable(),

                TextColumn::make('reason')
                    ->label('Alasan')
                    ->weight('medium')
                    ->description(fn (Dispute $record) => Str::limit($record->description, 25))
                    ->tooltip(fn (Dispute $record) => $record->description),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(function ($state): string {
                        $name = $state instanceof DisputeStatusState ? $state::$name : (string) $state;

                        return match ($name) {
                            'open' => 'Komplain Baru',
                            'seller_responded' => 'Seller Merespon',
                            'under_admin_review' => 'Mediasi Admin',
                            'resolved_refund' => 'Refund Disetujui',
                            'resolved_rejected' => 'Cair ke Seller',
                            default => ucfirst(str_replace('_', ' ', $name)),
                        };
                    })
                    ->icon(function ($state): string {
                        $name = $state instanceof DisputeStatusState ? $state::$name : (string) $state;

                        return match ($name) {
                            'open' => 'heroicon-m-exclamation-circle',
                            'seller_responded' => 'heroicon-m-chat-bubble-left-right',
                            'under_admin_review' => 'heroicon-m-magnifying-glass',
                            'resolved_refund' => 'heroicon-m-arrow-path-rounded-square',
                            'resolved_rejected' => 'heroicon-m-shield-check',
                            default => 'heroicon-m-ellipsis-horizontal',
                        };
                    })
                    ->color(function ($state): string {
                        $name = $state instanceof DisputeStatusState ? $state::$name : (string) $state;

                        return match ($name) {
                            'open' => 'danger',
                            'seller_responded' => 'warning',
                            'under_admin_review' => 'info',
                            'resolved_refund' => 'success',
                            'resolved_rejected' => 'gray',
                            default => 'gray',
                        };
                    }),

                TextColumn::make('created_at')
                    ->label('Diajukan')
                    ->dateTime('d M Y')
                    ->description(fn (Dispute $record) => $record->created_at?->diffForHumans())
                    ->sortable(),
            ])
            ->emptyStateHeading('Tidak Ada Sengketa Aktif')
            ->emptyStateDescription('Seluruh transaksi belanja di WhiMarket berjalan aman tanpa keluhan atau klaim pengembalian dana.')
            ->emptyStateIcon('heroicon-o-shield-check')
            ->recordActions([
                Action::make('view_details')
                    ->label('Investigasi')
                    ->icon('heroicon-o-eye')
                    ->iconButton()
                    ->color('primary')
                    ->tooltip('Buka Bukti & Mediasi Sengketa')
                    ->modalHeading(fn (Dispute $record) => 'Investigasi Mediasi Sengketa #'.($record->order?->order_number ?? $record->id))
                    ->modalWidth('3xl')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(fn (Dispute $record) => view('filament.modals.dispute-details', [
                        'dispute' => $record->loadMissing(['order.seller.user', 'order.items.variant.product', 'buyer', 'resolver']),
                    ])),

                Action::make('approve_refund')
                    ->label('Refund')
                    ->icon('heroicon-o-arrow-path-rounded-square')
                    ->color('danger')
                    ->iconButton()
                    ->tooltip('Kabulkan Refund ke Pembeli')
                    ->visible(fn (Dispute $record): bool => ! in_array($record->status::$name, ['resolved_refund', 'resolved_rejected']))
                    ->requiresConfirmation()
                    ->modalHeading('Kabulkan Komplain Buyer (Refund)')
                    ->modalDescription('Apakah Anda yakin ingin memenangkan komplain buyer dan memproses refund manual ke rekening pembeli?')
                    ->form([
                        Textarea::make('resolution_notes')
                            ->label('Catatan Keputusan Mediasi Admin')
                            ->placeholder('Jelaskan alasan pengembalian dana kepada pembeli')
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
                            $record->order->restoreStock();
                        }

                        Notification::make()
                            ->title('Dispute Dimenangkan Buyer (Refund Disetujui)')
                            ->success()
                            ->send();
                    }),

                Action::make('reject_dispute')
                    ->label('Cairkan')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->iconButton()
                    ->tooltip('Tolak Komplain & Cairkan ke Seller')
                    ->visible(fn (Dispute $record): bool => ! in_array($record->status::$name, ['resolved_refund', 'resolved_rejected']))
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Komplain Buyer (Cairkan ke Seller)')
                    ->modalDescription('Apakah Anda yakin bukti seller valid dan dana transaksi akan dicairkan ke penjual?')
                    ->form([
                        Textarea::make('resolution_notes')
                            ->label('Catatan Keputusan Mediasi Admin')
                            ->placeholder('Jelaskan alasan penolakan komplain pembeli')
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

                ActionGroup::make([
                    Action::make('view_order')
                        ->label('Lihat Pesanan di Admin')
                        ->icon('heroicon-o-shopping-bag')
                        ->url(fn (Dispute $record) => $record->order ? url('/admin/orders') : null)
                        ->visible(fn (Dispute $record) => (bool) $record->order_id),

                    Action::make('view_seller')
                        ->label('Kunjungi Toko Seller')
                        ->icon('heroicon-o-building-storefront')
                        ->url(fn (Dispute $record) => $record->order?->seller ? url('/seller/@'.$record->order->seller->username) : null)
                        ->openUrlInNewTab()
                        ->visible(fn (Dispute $record) => (bool) $record->order?->seller_id),

                    DeleteAction::make()
                        ->label('Hapus Kasus Sengketa')
                        ->icon('heroicon-o-trash'),
                ])
                    ->iconButton()
                    ->color('gray')
                    ->tooltip('Opsi Tindakan Lainnya'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageDisputes::route('/'),
        ];
    }
}
