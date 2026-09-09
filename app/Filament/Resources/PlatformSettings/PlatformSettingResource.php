<?php

namespace App\Filament\Resources\PlatformSettings;

use App\Filament\Resources\PlatformSettings\Pages\ManagePlatformSettings;
use App\Models\PlatformSetting;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class PlatformSettingResource extends Resource
{
    protected static ?string $model = PlatformSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan & Logistik';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Biaya Layanan & Fee Admin';

    protected static ?string $modelLabel = 'Pengaturan Biaya Layanan';

    protected static ?string $pluralModelLabel = 'Pengaturan Fee Admin & Biaya Layanan';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Konfigurasi Biaya Layanan (Fee Admin)')
                ->description('Atur nominal biaya penanganan transaksi escrow aman yang ditagihkan kepada pembeli saat checkout.')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->label('Nama Pengaturan')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('key')
                            ->label('Parameter Key')
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Identifier unik sistem (read-only).'),
                    ]),

                    Grid::make(2)->schema([
                        TextInput::make('value')
                            ->label('Besaran Biaya Layanan (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->minValue(0)
                            ->helperText('Nominal flat rupiah yang otomatis ditambahkan ke total transaksi pembeli saat checkout.'),

                        Toggle::make('is_active')
                            ->label('Aktifkan Pengenaan Fee Admin')
                            ->helperText('Jika dinonaktifkan, pembeli tidak dikenakan fee admin (bebas biaya layanan / Rp 0).')
                            ->default(true),
                    ]),

                    Textarea::make('description')
                        ->label('Keterangan / Informasi Biaya')
                        ->rows(3)
                        ->helperText('Penjelasan peruntukan biaya layanan untuk dokumentasi internal marketplace.'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Pengaturan')
                    ->weight('bold')
                    ->icon('heroicon-o-receipt-percent')
                    ->description(fn (PlatformSetting $record) => 'Parameter: '.$record->key)
                    ->searchable(),

                TextColumn::make('value')
                    ->label('Tarif Saat Ini')
                    ->formatStateUsing(function ($state, PlatformSetting $record) {
                        if (! $record->is_active) {
                            return 'Dinonaktifkan (Rp 0)';
                        }

                        return 'Rp '.number_format((float) $state, 0, ',', '.');
                    })
                    ->color(fn (PlatformSetting $record) => $record->is_active ? 'primary' : 'gray')
                    ->weight('bold'),

                TextColumn::make('is_active')
                    ->label('Status Pengenaan')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Aktif Dikenakan' : 'Dinonaktifkan')
                    ->color(fn ($state) => $state ? 'success' : 'gray'),

                TextColumn::make('description')
                    ->label('Keterangan Biaya')
                    ->limit(65)
                    ->wrap(),

                TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->since()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Ubah Tarif / Status')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->modalHeading('Ubah Pengaturan Biaya Layanan (Fee Admin)')
                    ->modalDescription('Tentukan nominal fee admin yang dikenakan kepada pembeli saat checkout atau nonaktifkan pengenaan biaya.')
                    ->successNotificationTitle('Pengaturan Fee Admin berhasil diperbarui!'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePlatformSettings::route('/'),
        ];
    }
}
