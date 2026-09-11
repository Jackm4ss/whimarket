<?php

namespace App\Filament\Resources\ShippingZones;

use App\Filament\Resources\ShippingZones\Pages\CreateShippingZone;
use App\Filament\Resources\ShippingZones\Pages\EditShippingZone;
use App\Filament\Resources\ShippingZones\Pages\ListShippingZones;
use App\Models\ShippingZone;
use BackedEnum;
use Creasi\Nusa\Models\Province;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class ShippingZoneResource extends Resource
{
    protected static ?string $model = ShippingZone::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan & Logistik';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Tarif Ongkir & Zonasi';

    protected static ?string $modelLabel = 'Zona Pengiriman';

    protected static ?string $pluralModelLabel = 'Pengaturan Tarif Ongkir / Zonasi';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema(self::getFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order', 'asc')
            ->recordActionsColumnLabel('Aksi')
            ->recordActionsAlignment('center')
            ->columns([
                TextColumn::make('zone_code')
                    ->label('Kode')
                    ->fontFamily('mono')
                    ->weight('bold')
                    ->color('primary')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Kode zona disalin!')
                    ->description(fn (ShippingZone $record) => "#{$record->sort_order}"),

                TextColumn::make('name')
                    ->label('Zona & Rute')
                    ->weight('bold')
                    ->searchable()
                    ->description(fn (ShippingZone $record) => Str::limit($record->courier_notes ?? 'Reguler J&T/JNE/SiCepat', 28))
                    ->tooltip(fn (ShippingZone $record) => $record->courier_notes),

                TextColumn::make('rate')
                    ->label('Tarif Normal')
                    ->formatStateUsing(fn ($state) => 'Rp '.number_format((float) $state, 0, ',', '.').' / kg')
                    ->weight('bold')
                    ->color('success')
                    ->description(fn (ShippingZone $record) => $record->is_free_shipping ? 'Promo: Bebas Ongkir' : 'Tarif flat reguler')
                    ->sortable(),

                TextColumn::make('etd')
                    ->label('Estimasi')
                    ->icon('heroicon-m-clock')
                    ->placeholder('-')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('provinces')
                    ->label('Cakupan')
                    ->state(fn (ShippingZone $record) => count($record->provinces ?? []).' Provinsi')
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-m-map-pin')
                    ->tooltip(fn (ShippingZone $record) => implode(', ', (array) ($record->provinces ?? []))),

                ToggleColumn::make('is_free_shipping')
                    ->label('Bebas Ongkir')
                    ->alignCenter()
                    ->tooltip('Toggle subsidi gratis ongkir seketika'),

                ToggleColumn::make('is_active')
                    ->label('Status')
                    ->alignCenter()
                    ->tooltip('Toggle status operasional zona pengiriman'),
            ])
            ->emptyStateHeading('Belum Ada Zona Logistik')
            ->emptyStateDescription('Tambahkan zona pengiriman untuk menentukan tarif flat ongkos kirim pembeli ke berbagai wilayah Indonesia.')
            ->emptyStateIcon('heroicon-o-truck')
            ->emptyStateActions([
                CreateAction::make()
                    ->label('Tambah Zona Pengiriman')
                    ->icon('heroicon-o-plus-circle'),
            ])
            ->recordActions([
                Action::make('view_provinces')
                    ->label('Cakupan')
                    ->icon('heroicon-o-map-pin')
                    ->iconButton()
                    ->color('info')
                    ->tooltip('Lihat Cakupan Provinsi')
                    ->modalHeading(fn (ShippingZone $record) => 'Cakupan Wilayah - '.$record->name)
                    ->modalWidth('2xl')
                    ->modalContent(fn (ShippingZone $record) => view('filament.modals.shipping-zone-provinces', ['zone' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                EditAction::make()
                    ->label('Ubah')
                    ->icon('heroicon-o-pencil-square')
                    ->iconButton()
                    ->color('primary')
                    ->tooltip('Ubah Konfigurasi Zona'),

                ActionGroup::make([
                    DeleteAction::make()
                        ->label('Hapus Zona'),
                ]),
            ]);
    }

    public static function getFormSchema(): array
    {
        return [
            Section::make('Identifikasi Zona & Wilayah')
                ->description('Atur kode pengenal, nama kluster zona wilayah, urutan prioritas, dan catatan rute ekspedisi.')
                ->icon('heroicon-o-map-pin')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('zone_code')
                            ->label('Kode Identifikasi Zona')
                            ->required()
                            ->maxLength(20)
                            ->placeholder('Contoh: ZONA-1')
                            ->helperText('Kode unik identifikasi sistem (misal ZONA-1, JABODETABEK).'),
                        TextInput::make('sort_order')
                            ->label('Urutan Prioritas Evaluasi')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->helperText('Urutan evaluasi sistem saat mencocokkan alamat pembeli.'),
                    ]),
                    TextInput::make('name')
                        ->label('Nama Kluster Zona Pengiriman')
                        ->required()
                        ->maxLength(100)
                        ->placeholder('Contoh: Zona 1 (Jabodetabek & Banten)')
                        ->helperText('Nama zona yang ditampilkan pada rincian ongkir saat checkout.'),
                    Textarea::make('courier_notes')
                        ->label('Catatan Rute Ekspedisi / Kurir Rekomendasi')
                        ->rows(2)
                        ->placeholder('Contoh: Ekspedisi J&T Express / SiCepat / JNE Reguler (Jalur Darat Prioritas)')
                        ->helperText('Informasi internal rute dan mitra logistik pengiriman pada zona ini.')
                        ->columnSpanFull(),
                ]),

            Section::make('Tarif Ongkos Kirim & Pengaturan Promo')
                ->description('Tentukan tarif flat per kilogram, estimasi waktu tempuh (ETD), serta status promo subsidi bebas ongkir.')
                ->icon('heroicon-o-banknotes')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('rate')
                            ->label('Nominal Tarif Ongkir Flat')
                            ->numeric()
                            ->prefix('Rp')
                            ->suffix('/ kg')
                            ->required()
                            ->helperText('Tarif dasar pengiriman yang dibebankan kepada pembeli pada zona ini.'),
                        TextInput::make('etd')
                            ->label('Estimasi Waktu Pengiriman (ETD)')
                            ->required()
                            ->maxLength(50)
                            ->placeholder('Contoh: 1-2 hari kerja')
                            ->helperText('Perkiraan durasi paket tiba sejak diserahkan ke kurir ekspedisi.'),
                    ]),
                    Grid::make(2)->schema([
                        Toggle::make('is_free_shipping')
                            ->label('Aktifkan Promo Bebas Ongkir (Subsidi 100%)')
                            ->helperText('Jika aktif, pembeli di zona ini mendapatkan gratis ongkir (Rp 0).'),
                        Toggle::make('is_active')
                            ->label('Status Operasional Zona Aktif')
                            ->default(true)
                            ->helperText('Nonaktifkan jika ingin menutup rute pengiriman ke wilayah zona ini sementara.'),
                    ]),
                ]),

            Section::make('Cakupan Wilayah Provinsi')
                ->description('Pilih seluruh provinsi di Indonesia yang masuk ke dalam tarif dan ketentuan zona ini (data tersinkronisasi Creasi Nusa).')
                ->icon('heroicon-o-globe-asia-australia')
                ->schema([
                    Select::make('provinces')
                        ->label('Daftar Provinsi Tercover')
                        ->multiple()
                        ->searchable()
                        ->options(function () {
                            try {
                                $options = Province::orderBy('name')->pluck('name', 'name')->toArray();
                                $options['DKI Jakarta'] = 'DKI Jakarta (Alias)';
                                $options['DI Yogyakarta'] = 'DI Yogyakarta (Alias)';

                                return $options;
                            } catch (\Throwable $e) {
                                return [];
                            }
                        })
                        ->required()
                        ->helperText('Pilih satu atau lebih provinsi untuk dipetakan ke zona tarif ini.')
                        ->columnSpanFull(),
                ]),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListShippingZones::route('/'),
            'create' => CreateShippingZone::route('/create'),
            'edit' => EditShippingZone::route('/{record}/edit'),
        ];
    }
}
