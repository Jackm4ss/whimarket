<?php

namespace App\Filament\Resources\Products;

use App\Enums\ProductCondition;
use App\Enums\ProductStatus;
use App\Filament\Resources\Products\Pages\ManageProducts;
use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
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

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label('Produk')
                    ->searchable(['name', 'slug'])
                    ->sortable()
                    ->formatStateUsing(function (Product $record) {
                        $imageUrl = e($record->primary_image_url);
                        $name = e($record->name);
                        $categoryName = e($record->category?->name ?? 'Katalog');
                        $priceFormatted = number_format((float) $record->price, 0, ',', '.');
                        $totalStock = (int) $record->total_stock;

                        $condLabel = $record->condition instanceof ProductCondition
                            ? $record->condition->label()
                            : ($record->condition ? ucfirst(str_replace('_', ' ', (string) $record->condition)) : 'Kondisi Baik');

                        $condBg = match ($record->condition) {
                            ProductCondition::BRAND_NEW, ProductCondition::LIKE_NEW => 'background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0;',
                            ProductCondition::VERY_GOOD => 'background: #EFF6FF; color: #1E40AF; border: 1px solid #BFDBFE;',
                            default => 'background: #F8FAFC; color: #475569; border: 1px solid #E2E8F0;',
                        };

                        $mobileMeta = '
                            <div class="sm:hidden" style="display: flex; align-items: center; gap: 6px; margin-top: 3px; font-size: 11px;">
                                <span style="font-weight: 800; color: #0F172A;">Rp '.$priceFormatted.'</span>
                                <span style="color: #94A3B8;">&bull;</span>
                                <span style="font-weight: 600; color: '.($totalStock > 0 ? '#059669' : '#DC2626').';">Stok: '.$totalStock.'</span>
                            </div>
                        ';

                        return new HtmlString('
                            <div style="display: flex; align-items: center; gap: 10px; min-width: 0;">
                                <div style="position: relative; width: 44px; height: 44px; border-radius: 8px; overflow: hidden; border: 1px solid #E2E8F0; background: #F8FAFC; flex-shrink: 0;">
                                    <img
                                        src="'.$imageUrl.'"
                                        alt="'.$name.'"
                                        style="width: 100%; height: 100%; object-fit: cover;"
                                        onerror="this.src=\'/assets/products/prod-hoodie.png\'"
                                    />
                                </div>
                                <div style="display: flex; flex-direction: column; min-width: 0;">
                                    <span style="font-weight: 700; color: #0F172A; font-size: 13px; line-height: 1.25; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; max-width: 185px;">
                                        '.$name.'
                                    </span>
                                    <div style="display: flex; align-items: center; gap: 4px; margin-top: 2px; flex-wrap: nowrap;">
                                        <span style="display: inline-flex; align-items: center; padding: 1px 5px; border-radius: 9999px; font-size: 10px; font-weight: 700; background: #F3EEFF; color: #4F26A6; border: 1px solid #DDD6FE; white-space: nowrap;">
                                            '.$categoryName.'
                                        </span>
                                        <span style="display: inline-flex; align-items: center; padding: 1px 5px; border-radius: 9999px; font-size: 10px; font-weight: 600; '.$condBg.' white-space: nowrap;">
                                            '.$condLabel.'
                                        </span>
                                    </div>
                                    '.$mobileMeta.'
                                </div>
                            </div>
                        ');
                    }),

                TextColumn::make('seller.store_name')
                    ->label('Toko Mitra')
                    ->searchable()
                    ->visibleFrom('sm')
                    ->formatStateUsing(function (Product $record) {
                        $seller = $record->seller;
                        if (! $seller) {
                            return new HtmlString('<span style="color: #94A3B8; font-size: 12px;">Tanpa Seller</span>');
                        }

                        $storeName = e($seller->store_name);
                        $username = e($seller->username);
                        $isVerified = $seller->isVerified();

                        $verifiedBadge = $isVerified ? '
                            <svg style="width: 13px; height: 13px; color: #4F26A6; display: inline-block; vertical-align: middle; margin-left: 3px; flex-shrink: 0;" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                            </svg>
                        ' : '';

                        $initial = strtoupper(substr($storeName, 0, 1));

                        return new HtmlString('
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 28px; height: 28px; border-radius: 50%; background: #FAF5FF; color: #6B21A8; border: 1px solid #E9D5FF; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; flex-shrink: 0;">
                                    '.$initial.'
                                </div>
                                <div style="display: flex; flex-direction: column; min-width: 0;">
                                    <div style="font-weight: 700; color: #0F172A; font-size: 12.5px; line-height: 1.2; display: flex; align-items: center;">
                                        <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 130px;">'.$storeName.'</span>
                                        '.$verifiedBadge.'
                                    </div>
                                    <span style="font-size: 11px; color: #6B21A8; font-weight: 600; margin-top: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 130px;">@'.$username.'</span>
                                </div>
                            </div>
                        ');
                    }),

                TextColumn::make('price')
                    ->label('Harga & Stok')
                    ->sortable()
                    ->visibleFrom('sm')
                    ->formatStateUsing(function (Product $record) {
                        $priceFormatted = number_format((float) $record->price, 0, ',', '.');
                        $totalStock = (int) $record->total_stock;
                        $variantsCount = $record->variants->count();

                        $stockHtml = $totalStock > 0 ? '
                            <div style="display: flex; align-items: center; gap: 4px; font-size: 11px; color: #059669; font-weight: 600; margin-top: 3px;">
                                <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #10B981; flex-shrink: 0;"></span>
                                <span>Stok: '.$totalStock.'</span>
                                <span style="color: #64748B; font-weight: 400;">('.$variantsCount.' varian)</span>
                            </div>
                        ' : '
                            <div style="display: flex; align-items: center; gap: 4px; font-size: 11px; color: #DC2626; font-weight: 600; margin-top: 3px;">
                                <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #EF4444; flex-shrink: 0;"></span>
                                <span>Habis (0 stok)</span>
                            </div>
                        ';

                        return new HtmlString('
                            <div style="display: flex; flex-direction: column;">
                                <span style="font-weight: 800; color: #0F172A; font-size: 13.5px; letter-spacing: -0.01em;">
                                    Rp '.$priceFormatted.'
                                </span>
                                '.$stockHtml.'
                            </div>
                        ');
                    }),

                TextColumn::make('status')
                    ->label('Status Moderasi')
                    ->visibleFrom('sm')
                    ->formatStateUsing(function (Product $record) {
                        $status = $record->status;
                        $statusBadge = $status === ProductStatus::ACTIVE
                            ? '<span style="display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 9999px; background: #ECFDF5; color: #047857; border: 1px solid #A7F3D0; font-size: 11px; font-weight: 700;">● Aktif (Live)</span>'
                            : '<span style="display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 9999px; background: #F1F5F9; color: #475569; border: 1px solid #CBD5E1; font-size: 11px; font-weight: 700;">○ Nonaktif</span>';

                        $date = $record->created_at?->format('d M Y') ?? '-';

                        return new HtmlString('
                            <div style="display: flex; flex-direction: column; align-items: flex-start;">
                                '.$statusBadge.'
                                <span style="font-size: 10.5px; color: #64748B; margin-top: 3px;">
                                    Tayang: '.$date.'
                                </span>
                            </div>
                        ');
                    }),

                TextColumn::make('wishlists_count')
                    ->label('Peminat')
                    ->counts('wishlists')
                    ->sortable()
                    ->visibleFrom('lg')
                    ->formatStateUsing(function (Product $record) {
                        $count = (int) ($record->wishlists_count ?? 0);

                        return new HtmlString('
                            <div style="display: flex; align-items: center; gap: 4px; font-size: 12px; color: #E11D48; font-weight: 700;">
                                <svg style="width: 14px; height: 14px; fill: #F43F5E; flex-shrink: 0;" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                                <span>'.$count.' Suka</span>
                            </div>
                        ');
                    }),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Kategori Produk')
                    ->options(fn () => Category::query()->pluck('name', 'id')->toArray()),

                SelectFilter::make('status')
                    ->label('Status Moderasi')
                    ->options([
                        'active' => 'Aktif (Live)',
                        'inactive' => 'Nonaktif',
                    ]),
                SelectFilter::make('condition')
                    ->label('Kondisi Barang')
                    ->options([
                        'brand_new' => 'Baru',
                        'like_new' => 'Seperti Baru',
                        'very_good' => 'Sangat Baik',
                        'good' => 'Baik',
                        'fair' => 'Cukup',
                    ]),

                SelectFilter::make('seller_id')
                    ->label('Toko Mitra')
                    ->options(fn () => Seller::query()->pluck('store_name', 'id')->toArray()),
            ])
            ->recordActions([
                Action::make('view_details')
                    ->label('Detail Produk')
                    ->tooltip('Rincian Spesifikasi & Moderasi')
                    ->icon('heroicon-o-eye')
                    ->iconButton()
                    ->color('primary')
                    ->modalHeading(fn (Product $record) => 'Rincian & Moderasi Produk - '.$record->name)
                    ->modalWidth('2xl')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(fn (Product $record) => view('filament.modals.product-details', [
                        'product' => $record->loadMissing(['seller.user', 'category', 'variants', 'images', 'primaryImage']),
                    ])),

                ActionGroup::make([
                    Action::make('open_storefront')
                        ->label('Buka di Storefront')
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->color('gray')
                        ->url(fn (Product $record) => url('/produk/'.$record->slug))
                        ->openUrlInNewTab(),

                    Action::make('open_seller_store')
                        ->label('Kunjungi Toko Seller')
                        ->icon('heroicon-o-building-storefront')
                        ->color('gray')
                        ->visible(fn (Product $record) => (bool) $record->seller)
                        ->url(fn (Product $record) => url('/seller/@'.$record->seller?->username))
                        ->openUrlInNewTab(),

                    Action::make('toggle_status')
                        ->label('Ubah Status (Aktif / Nonaktif)')
                        ->icon('heroicon-o-arrows-right-left')
                        ->color('warning')
                        ->action(function (Product $record) {
                            $newStatus = $record->status === ProductStatus::ACTIVE ? ProductStatus::INACTIVE : ProductStatus::ACTIVE;
                            $record->update(['status' => $newStatus]);
                            $label = $newStatus === ProductStatus::ACTIVE ? 'Aktif (Live)' : 'Nonaktif';
                            Notification::make()
                                ->title('Status Produk Diubah: '.$label)
                                ->success()
                                ->send();
                        }),

                    DeleteAction::make()->label('Hapus Produk'),
                ])
                    ->iconButton()
                    ->tooltip('Opsi Menu Produk'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageProducts::route('/'),
        ];
    }
}
