<?php

namespace App\Filament\Resources\ShippingZones;

use App\Filament\Resources\ShippingZones\Pages\CreateShippingZone;
use App\Filament\Resources\ShippingZones\Pages\EditShippingZone;
use App\Filament\Resources\ShippingZones\Pages\ListShippingZones;
use App\Models\ShippingZone;
use BackedEnum;
use Creasi\Nusa\Models\Province;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
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
            ->columns([
                TextColumn::make('zone_code')
                    ->label('Kode')
                    ->fontFamily('mono')
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nama Zona')
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('rate')
                    ->label('Tarif Normal')
                    ->formatStateUsing(fn ($state) => 'Rp '.number_format((float) $state, 0, ',', '.'))
                    ->weight('bold')
                    ->color('primary')
                    ->sortable(),
                TextColumn::make('etd')
                    ->label('Estimasi')
                    ->placeholder('-'),
                IconColumn::make('is_free_shipping')
                    ->label('Gratis Ongkir')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->alignCenter(),
                TextColumn::make('provinces')
                    ->label('Cakupan')
                    ->state(fn (ShippingZone $record) => count($record->provinces ?? []).' Provinsi')
                    ->badge()
                    ->color('info'),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Ubah')
                    ->icon('heroicon-o-pencil-square'),
                DeleteAction::make(),
            ]);
    }

    public static function getFormSchema(): array
    {
        return [
            Grid::make(2)->schema([
                TextInput::make('zone_code')
                    ->label('Kode Zona')
                    ->required()
                    ->maxLength(20)
                    ->placeholder('Contoh: ZONA-1')
                    ->helperText('Kode unik identifikasi zona.'),
                TextInput::make('sort_order')
                    ->label('Urutan Tampilan')
                    ->numeric()
                    ->default(1)
                    ->required(),
            ]),
            TextInput::make('name')
                ->label('Nama Zona Pengiriman')
                ->required()
                ->maxLength(100)
                ->placeholder('Contoh: Zona 1 (Jabodetabek & Jawa Barat)'),
            Grid::make(2)->schema([
                TextInput::make('rate')
                    ->label('Nominal Tarif Ongkir (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->required()
                    ->helperText('Tarif yang dibebankan kepada pembeli pada zona ini.'),
                TextInput::make('etd')
                    ->label('Estimasi Waktu Pengiriman')
                    ->required()
                    ->maxLength(50)
                    ->placeholder('Contoh: 1-2 hari kerja'),
            ]),
            Grid::make(2)->schema([
                Toggle::make('is_free_shipping')
                    ->label('Aktifkan Subsidi / Promo Gratis Ongkir')
                    ->helperText('Jika aktif, pembeli di zona ini mendapatkan ongkir Rp 0 (gratis).'),
                Toggle::make('is_active')
                    ->label('Status Zona Aktif')
                    ->default(true)
                    ->helperText('Nonaktifkan jika ingin menutup pengiriman ke zona ini sementara.'),
            ]),
            Select::make('provinces')
                ->label('Daftar Provinsi dalam Zona Ini')
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
                ->helperText('Pilih satu atau beberapa provinsi yang masuk dalam tarif zona ini (data dari Creasi Nusa).'),
            Textarea::make('courier_notes')
                ->label('Catatan Ekspedisi / Internal')
                ->rows(2)
                ->placeholder('Contoh: Menggunakan jalur darat J&T / JNE Reguler')
                ->columnSpanFull(),
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
