<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages\ManageOrders;
use App\Models\Order;
use App\States\Order\Delivered;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static ?string $navigationLabel = 'Manajemen Pesanan';

    protected static ?string $modelLabel = 'Pesanan';

    protected static ?string $pluralModelLabel = 'Daftar Seluruh Pesanan';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('buyer.name')
                    ->label('Pembeli')
                    ->searchable(),
                TextColumn::make('seller.store_name')
                    ->label('Toko Penjual')
                    ->searchable(),
                TextColumn::make('grand_total')
                    ->label('Total Transaksi')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending_payment' => 'warning',
                        'payment_verification', 'processing' => 'purple',
                        'paid', 'delivered', 'completed' => 'success',
                        'shipped' => 'warning',
                        'disputed', 'cancelled' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Tanggal Order')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('verify_delivery_claim')
                    ->label('Verifikasi Paket Sampai (Jalur B)')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi Klaim Paket Sampai Kurir')
                    ->modalDescription('Pastikan Anda telah memeriksa status DELIVERED pada tautan cek resi kurir eksternal. Apakah Anda ingin mengonfirmasi bahwa paket telah tiba?')
                    ->visible(fn (Order $record): bool => $record->status::$name === 'shipped')
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

                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageOrders::route('/'),
        ];
    }
}
