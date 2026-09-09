<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages\ManageOrders;
use App\Models\Order;
use App\States\Order\Delivered;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static string|UnitEnum|null $navigationGroup = 'Pesanan & Transaksi';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Manajemen Pesanan';

    protected static ?string $modelLabel = 'Pesanan';

    protected static ?string $pluralModelLabel = 'Daftar Seluruh Pesanan';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Nomor pesanan disalin!')
                    ->weight('bold')
                    ->icon('heroicon-o-shopping-bag')
                    ->description(fn (Order $record) => $record->items->count().' item produk'),

                TextColumn::make('buyer.name')
                    ->label('Pembeli')
                    ->formatStateUsing(fn ($state, Order $record) => $record->buyer?->name ?? $record->address_snapshot['recipient_name'] ?? 'Pembeli')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Order $record) => $record->buyer?->email ?? ($record->address_snapshot['phone'] ?? '-')),

                TextColumn::make('seller.store_name')
                    ->label('Toko Penjual')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-building-storefront')
                    ->url(fn (Order $record) => $record->seller ? route('filament.admin.resources.sellers.index', ['tableSearch' => $record->seller->store_name]) : null)
                    ->openUrlInNewTab()
                    ->tooltip(fn (Order $record) => $record->seller ? 'Buka Toko: '.$record->seller->store_name : null),

                TextColumn::make('grand_total')
                    ->label('Total Transaksi')
                    ->formatStateUsing(fn ($state) => 'Rp '.number_format((float) $state, 0, ',', '.'))
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Order $record) => 'Ongkir: Rp '.number_format((float) ($record->shipping_cost ?? 0), 0, ',', '.')),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => match ((string) $state) {
                        'pending_payment' => 'Menunggu Bayar',
                        'payment_verification' => 'Verifikasi Bayar',
                        'processing' => 'Perlu Diproses',
                        'shipped' => 'Sedang Dikirim',
                        'delivered', 'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        'disputed' => 'Dalam Sengketa',
                        default => ucfirst((string) $state),
                    })
                    ->color(fn ($state): string => match ((string) $state) {
                        'pending_payment' => 'gray',
                        'payment_verification' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'primary',
                        'delivered', 'completed' => 'success',
                        'cancelled', 'disputed' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn ($state): string => match ((string) $state) {
                        'pending_payment' => 'heroicon-m-clock',
                        'payment_verification' => 'heroicon-m-magnifying-glass',
                        'processing' => 'heroicon-m-arrow-path',
                        'shipped' => 'heroicon-m-truck',
                        'delivered', 'completed' => 'heroicon-m-check-circle',
                        'cancelled' => 'heroicon-m-x-circle',
                        'disputed' => 'heroicon-m-exclamation-triangle',
                        default => 'heroicon-m-ellipsis-horizontal',
                    }),

                TextColumn::make('created_at')
                    ->label('Tanggal Order')
                    ->dateTime('d M Y, H:i')
                    ->description(fn (Order $record) => $record->created_at?->diffForHumans())
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('view_details')
                    ->label('Detail Pesanan')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->modalHeading(fn (Order $record) => 'Rincian Pesanan #'.$record->order_number)
                    ->modalWidth('2xl')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(fn (Order $record) => view('filament.modals.order-details', ['order' => $record->loadMissing(['items.variant.product.images', 'items.variant.product.category', 'shipment', 'buyer', 'seller'])])),

                Action::make('verify_delivery_claim')
                    ->label('Verifikasi Paket Sampai')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi Klaim Paket Sampai Kurir')
                    ->modalDescription('Pastikan Anda telah memeriksa status DELIVERED pada tautan cek resi kurir eksternal. Apakah Anda ingin mengonfirmasi bahwa paket telah tiba?')
                    ->visible(fn (Order $record): bool => (string) $record->status === 'shipped')
                    ->action(function (Order $record) {
                        $record->status->transitionTo(Delivered::class);
                        $record->update([
                            'inspection_deadline_at' => now()->addHours(48),
                        ]);

                        if ($record->shipment) {
                            $record->shipment->update(['delivered_at' => now()]);
                        }

                        Notification::make()
                            ->title('Status Pesanan Berubah Menjadi Delivered! Countdown 48 Jam Aktif.')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageOrders::route('/'),
        ];
    }
}
