<?php

namespace App\Filament\Resources\Products;

use App\Enums\ProductStatus;
use App\Filament\Resources\Products\Pages\ManageProducts;
use App\Models\Product;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|UnitEnum|null $navigationGroup = 'Katalog & Produk';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Moderasi Produk';

    protected static ?string $modelLabel = 'Produk';

    protected static ?string $pluralModelLabel = 'Katalog & Moderasi Produk';

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('primary_image_url')
                    ->label('Foto')
                    ->disk('public'),
                TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('seller.store_name')
                    ->label('Toko Seller')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Kategori'),
                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (ProductStatus $state): string => match ($state) {
                        ProductStatus::ACTIVE => 'success',
                        ProductStatus::INACTIVE => 'gray',
                        ProductStatus::REJECTED => 'danger',
                    }),
                TextColumn::make('created_at')
                    ->label('Tanggal Tayang')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('toggle_status')
                    ->label('Ubah Status Aktif')
                    ->icon('heroicon-o-arrows-right-left')
                    ->action(function (Product $record) {
                        $newStatus = $record->status === ProductStatus::ACTIVE ? ProductStatus::INACTIVE : ProductStatus::ACTIVE;
                        $record->update(['status' => $newStatus]);
                        Notification::make()->title('Status Produk Diperbarui')->success()->send();
                    }),

                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageProducts::route('/'),
        ];
    }
}
