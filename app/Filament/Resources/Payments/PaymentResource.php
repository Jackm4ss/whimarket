<?php

namespace App\Filament\Resources\Payments;

use App\Enums\PaymentStatus;
use App\Filament\Resources\Payments\Pages\ManagePayments;
use App\Models\Payment;
use App\States\Order\Cancelled;
use App\States\Order\Paid;
use App\States\Order\PendingPayment;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static string|UnitEnum|null $navigationGroup = 'Pesanan & Transaksi';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Verifikasi Pembayaran';

    protected static ?string $modelLabel = 'Pembayaran';

    protected static ?string $pluralModelLabel = 'Verifikasi Pembayaran Transfer';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('order.order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-shopping-bag')
                    ->copyable()
                    ->copyMessage('Nomor pesanan disalin!')
                    ->description(function (Payment $record) {
                        $buyerName = $record->order?->buyer?->name ?? 'Pembeli';
                        $phone = $record->order?->address_snapshot['phone'] ?? $record->order?->buyer?->phone;

                        return $phone ? "{$buyerName} • {$phone}" : $buyerName;
                    }),

                TextColumn::make('order_product')
                    ->label('Produk Dipesan')
                    ->state(fn (Payment $record) => $record->order?->items?->first()?->product_name_snapshot ?? 'Produk WhiMarket')
                    ->description(fn (Payment $record) => $record->order && $record->order->items->count() > 1
                        ? '+'.($record->order->items->count() - 1).' produk lainnya'
                        : ($record->order?->items?->first()?->variant_name_snapshot ? 'Varian: '.$record->order->items->first()->variant_name_snapshot : '1 item'))
                    ->weight('medium')
                    ->wrap(),

                TextColumn::make('order.seller.store_name')
                    ->label('Toko Penjual')
                    ->icon('heroicon-o-building-storefront')
                    ->weight('bold')
                    ->url(fn (Payment $record) => $record->order?->seller ? route('filament.admin.resources.sellers.index', ['tableSearch' => $record->order->seller->store_name]) : null)
                    ->openUrlInNewTab()
                    ->tooltip('Buka Toko Seller')
                    ->description(function (Payment $record) {
                        $sellerUser = $record->order?->seller?->user;
                        $email = $sellerUser?->email;
                        $phone = $sellerUser?->phone;
                        if ($email && $phone) {
                            return "{$email} • {$phone}";
                        }

                        return $email ?: ($phone ?: null);
                    })
                    ->placeholder('-'),

                TextColumn::make('amount')
                    ->label('Nominal Transfer')
                    ->formatStateUsing(fn ($state) => 'Rp '.number_format((float) $state, 0, ',', '.'))
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Payment $record) => ($record->sender_bank_name ? $record->sender_bank_name.' → ' : '').($record->bank_destination ?: 'BCA').' Escrow'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof PaymentStatus ? $state->label() : (PaymentStatus::tryFrom((string) $state)?->label() ?? ucfirst((string) $state)))
                    ->color(fn ($state): string => match ($state instanceof PaymentStatus ? $state : PaymentStatus::tryFrom((string) $state)) {
                        PaymentStatus::VERIFIED => 'success',
                        PaymentStatus::PENDING_REVIEW => 'warning',
                        PaymentStatus::REJECTED => 'danger',
                        PaymentStatus::UNPAID => 'gray',
                        default => 'gray',
                    })
                    ->icon(fn ($state): string => match ($state instanceof PaymentStatus ? $state : PaymentStatus::tryFrom((string) $state)) {
                        PaymentStatus::VERIFIED => 'heroicon-m-check-circle',
                        PaymentStatus::PENDING_REVIEW => 'heroicon-m-clock',
                        PaymentStatus::REJECTED => 'heroicon-m-x-circle',
                        PaymentStatus::UNPAID => 'heroicon-m-minus-circle',
                        default => 'heroicon-m-ellipsis-horizontal',
                    }),

                TextColumn::make('created_at')
                    ->label('Waktu Upload')
                    ->dateTime('d M Y, H:i')
                    ->description(fn (Payment $record) => $record->created_at?->diffForHumans())
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('view_details')
                    ->label('Detail Bayar')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->modalHeading(fn (Payment $record) => 'Rincian Pembayaran #'.$record->id.' (Pesanan #'.($record->order?->order_number ?? '-').')')
                    ->modalWidth('2xl')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(fn (Payment $record) => view('filament.modals.payment-details', [
                        'payment' => $record->loadMissing([
                            'order.buyer',
                            'order.seller.user.addresses',
                            'order.items.variant.product.category',
                            'order.items.variant.product.images',
                        ]),
                    ]))
                    ->extraModalActions([
                        Action::make('approve')
                            ->label('Setujui Pembayaran (Valid)')
                            ->icon('heroicon-o-check-badge')
                            ->color('success')
                            ->requiresConfirmation()
                            ->modalHeading('Verifikasi Pembayaran Valid')
                            ->modalDescription('Apakah Anda yakin transfer pembayaran ini valid dan mutasi dana telah masuk ke rekening escrow WhiMarket?')
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
                                Radio::make('order_action')
                                    ->label('Tindakan Pesanan & Stok')
                                    ->options([
                                        'cancel_and_restore' => 'Tolak Bukti & Batalkan Pesanan (Stok Dikembalikan Otomatis)',
                                        'request_reupload' => 'Minta Pembeli Unggah Ulang Bukti (Stok Tetap Ditahan)',
                                    ])
                                    ->descriptions([
                                        'cancel_and_restore' => 'Pesanan dibatalkan otomatis dan seluruh stok barang dikembalikan ke etalase toko.',
                                        'request_reupload' => 'Pesanan kembali ke Menunggu Pembayaran agar pembeli dapat mengunggah bukti transfer yang benar.',
                                    ])
                                    ->default('cancel_and_restore')
                                    ->required(),
                            ])
                            ->action(function (Payment $record, array $data) {
                                $record->update([
                                    'status' => PaymentStatus::REJECTED,
                                    'rejection_reason' => $data['rejection_reason'],
                                    'verified_by' => Auth::id(),
                                    'verified_at' => now(),
                                ]);

                                $order = $record->order;
                                if ($order) {
                                    if (($data['order_action'] ?? 'cancel_and_restore') === 'cancel_and_restore') {
                                        if ($order->status->canTransitionTo(Cancelled::class)) {
                                            $order->status->transitionTo(Cancelled::class);
                                        } else {
                                            $order->forceFill(['status' => Cancelled::class])->save();
                                        }
                                        $order->restoreStock();

                                        Notification::make()
                                            ->title('Bukti Pembayaran Ditolak & Pesanan Dibatalkan')
                                            ->body('Stok produk telah dikembalikan ke etalase dan pembeli dapat melihat alasan penolakan.')
                                            ->danger()
                                            ->send();
                                    } else {
                                        if ($order->status->canTransitionTo(PendingPayment::class)) {
                                            $order->status->transitionTo(PendingPayment::class);
                                        } else {
                                            $order->forceFill(['status' => PendingPayment::class])->save();
                                        }

                                        Notification::make()
                                            ->title('Bukti Pembayaran Ditolak')
                                            ->body('Status pesanan dikembalikan ke Menunggu Pembayaran agar pembeli dapat mengunggah ulang.')
                                            ->warning()
                                            ->send();
                                    }
                                } else {
                                    Notification::make()
                                        ->title('Bukti Pembayaran Ditolak')
                                        ->danger()
                                        ->send();
                                }
                            }),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePayments::route('/'),
        ];
    }
}
