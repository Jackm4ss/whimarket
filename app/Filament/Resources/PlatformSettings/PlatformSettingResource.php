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
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class PlatformSettingResource extends Resource
{
    protected static ?string $model = PlatformSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan & Logistik';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Biaya Layanan & Fee Admin';

    protected static ?string $modelLabel = 'Pengaturan Sistem';

    protected static ?string $pluralModelLabel = 'Pengaturan Fee Admin & Biaya Layanan';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema(self::getFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'asc')
            ->recordActionsColumnLabel('Aksi')
            ->recordActionsAlignment('center')
            ->columns([
                TextColumn::make('name')
                    ->label('Parameter')
                    ->weight('bold')
                    ->color('primary')
                    ->icon(fn (PlatformSetting $record) => match ($record->type) {
                        'currency' => 'heroicon-m-banknotes',
                        'hours' => 'heroicon-m-clock',
                        default => 'heroicon-m-adjustments-vertical',
                    })
                    ->description(fn (PlatformSetting $record) => "Key: {$record->key}")
                    ->searchable(),

                TextColumn::make('value')
                    ->label('Nilai Parameter')
                    ->weight('bold')
                    ->color(fn (PlatformSetting $record) => $record->is_active ? 'success' : 'gray')
                    ->formatStateUsing(function ($state, PlatformSetting $record): string {
                        if (! $record->is_active) {
                            return 'Dinonaktifkan';
                        }
                        if ($record->type === 'currency') {
                            return 'Rp '.number_format((float) $state, 0, ',', '.');
                        }
                        if ($record->type === 'hours') {
                            return "{$state} Jam";
                        }

                        return (string) $state;
                    })
                    ->description(function (PlatformSetting $record): string {
                        if (! $record->is_active) {
                            return 'Bebas biaya / promo';
                        }
                        if ($record->key === 'admin_fee') {
                            return 'Per transaksi';
                        }
                        if ($record->key === 'free_shipping_min_order') {
                            return 'Min. keranjang';
                        }
                        if ($record->key === 'inspection_deadline_hours') {
                            return 'Garansi komplain';
                        }
                        if ($record->key === 'payment_timeout_hours') {
                            return 'Batas transfer';
                        }

                        return 'Nilai aktif';
                    })
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'currency' => 'Rupiah (Rp)',
                        'hours' => 'Waktu (Jam)',
                        default => ucfirst((string) $state),
                    })
                    ->color(fn ($state) => match ($state) {
                        'currency' => 'info',
                        'hours' => 'warning',
                        default => 'gray',
                    }),

                ToggleColumn::make('is_active')
                    ->label('Status')
                    ->alignCenter()
                    ->tooltip('Toggle aktif/nonaktif parameter seketika'),

                TextColumn::make('description')
                    ->label('Keterangan')
                    ->limit(30)
                    ->tooltip(fn (PlatformSetting $record) => $record->description),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->since()
                    ->tooltip(fn (PlatformSetting $record) => $record->updated_at?->translatedFormat('d M Y, H:i'))
                    ->sortable(),
            ])
            ->emptyStateHeading('Belum Ada Pengaturan Sistem')
            ->emptyStateDescription('Parameter sistem marketplace belum dikonfigurasi.')
            ->emptyStateIcon('heroicon-o-adjustments-vertical')
            ->recordActions([
                EditAction::make()
                    ->label('Ubah')
                    ->icon('heroicon-o-pencil-square')
                    ->iconButton()
                    ->color('primary')
                    ->tooltip('Ubah Nilai / Konfigurasi')
                    ->modalHeading(fn (PlatformSetting $record) => 'Ubah Parameter: '.$record->name)
                    ->modalDescription('Sesuaikan besaran nilai parameter operasional marketplace atau ubah status pengenaan.')
                    ->modalWidth('xl')
                    ->modalSubmitActionLabel('Simpan Perubahan')
                    ->modalCancelActionLabel('Batal')
                    ->successNotificationTitle('Pengaturan sistem berhasil diperbarui!'),
            ]);
    }

    public static function getFormSchema(): array
    {
        return [
            Grid::make(2)->schema([
                TextInput::make('name')
                    ->label('Nama Parameter')
                    ->required()
                    ->maxLength(100),

                TextInput::make('key')
                    ->label('Parameter Key Identifikasi')
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText('Identifier unik sistem (hanya-baca).'),

                TextInput::make('value')
                    ->label('Besaran Nilai Parameter')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->prefix(fn (?PlatformSetting $record) => $record?->type === 'currency' ? 'Rp' : null)
                    ->suffix(fn (?PlatformSetting $record) => $record?->type === 'hours' ? 'Jam' : null)
                    ->helperText('Nilai yang digunakan untuk kalkulasi transaksi.'),

                Toggle::make('is_active')
                    ->label('Status Parameter Aktif')
                    ->helperText('Jika dinonaktifkan, parameter tidak membebankan biaya.')
                    ->default(true),

                Textarea::make('description')
                    ->label('Keterangan Operasional')
                    ->rows(3)
                    ->helperText('Penjelasan peruntukan dan dampak parameter pada alur checkout.')
                    ->columnSpanFull(),
            ]),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePlatformSettings::route('/'),
        ];
    }
}
