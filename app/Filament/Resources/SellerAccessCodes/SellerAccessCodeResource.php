<?php

namespace App\Filament\Resources\SellerAccessCodes;

use App\Filament\Resources\SellerAccessCodes\Pages\CreateSellerAccessCode;
use App\Filament\Resources\SellerAccessCodes\Pages\EditSellerAccessCode;
use App\Filament\Resources\SellerAccessCodes\Pages\ListSellerAccessCodes;
use App\Models\SellerAccessCode;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class SellerAccessCodeResource extends Resource
{
    protected static ?string $model = SellerAccessCode::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    protected static string|UnitEnum|null $navigationGroup = 'Mitra Toko (Seller)';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Kode Akses VIP Seller';

    protected static ?string $modelLabel = 'Kode Akses';

    protected static ?string $pluralModelLabel = 'Manajemen Kode Akses VIP Seller';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Pengaturan Kode Akses VIP Seller')
                ->description('Tentukan kode registrasi, batas kuota penggunaan, proteksi kunci (lock), dan target calon seller.')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('code')
                            ->label('Kode Akses VIP')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->default(fn () => 'WHI-VIP-'.strtoupper(Str::random(6)))
                            ->helperText('Kode unik aktivasi pendaftaran seller VIP.')
                            ->suffixAction(
                                Action::make('regenerate')
                                    ->icon('heroicon-o-arrow-path')
                                    ->tooltip('Generate Ulang Kode Acak')
                                    ->action(function ($set) {
                                        $set('code', 'WHI-VIP-'.strtoupper(Str::random(6)));
                                    })
                            ),

                        TextInput::make('email')
                            ->label('Batasi Khusus Email Calon Seller (Opsional)')
                            ->email()
                            ->placeholder('nama@email.com (Kosongkan jika bisa dipakai siapa saja)')
                            ->helperText('Jika diisi, hanya email ini yang dapat mendaftar dengan kode ini.'),
                    ]),

                    Radio::make('quota_type')
                        ->label('Tipe Kuota Penggunaan')
                        ->options([
                            'limited' => 'Terbatas (Bisa digunakan maksimal N kali, kuota bisa direset kapan saja)',
                            'one_time' => '1x Pakai (Langsung terkunci otomatis setelah 1x digunakan)',
                            'unlimited' => 'Tanpa Batasan (Bisa digunakan berkali-kali tanpa batas)',
                        ])
                        ->default('limited')
                        ->live()
                        ->required(),

                    TextInput::make('max_uses')
                        ->label('Batas Maksimal Penggunaan (Kuota)')
                        ->numeric()
                        ->default(10)
                        ->minValue(1)
                        ->visible(fn ($get) => $get('quota_type') === 'limited')
                        ->required(fn ($get) => $get('quota_type') === 'limited')
                        ->helperText('Contoh: 10x penggunaan. Admin dapat mereset kuota penggunaan kembali ke 0 kapan saja.'),

                    Toggle::make('is_locked')
                        ->label('Kunci Kode Akses Ini (Lock)')
                        ->helperText('Jika dikunci, kode langsung nonaktif dan tidak bisa digunakan mendaftar.')
                        ->default(false),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('code')
                    ->label('Kode Akses VIP')
                    ->fontFamily('mono')
                    ->weight('bold')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Kode disalin ke clipboard!'),
                TextColumn::make('email')
                    ->label('Email Calon Seller')
                    ->placeholder('Siapapun (Bebas)')
                    ->searchable(),
                TextColumn::make('usage')
                    ->label('Kuota Penggunaan')
                    ->state(function (SellerAccessCode $record): string {
                        if ($record->is_one_time) {
                            return '1x Pakai ('.$record->used_count.'/1)';
                        }
                        if ($record->max_uses === null) {
                            return 'Tanpa Batas ('.$record->used_count.'x dipakai)';
                        }

                        return $record->used_count.' / '.$record->max_uses.' Terpakai';
                    })
                    ->badge()
                    ->color(function (SellerAccessCode $record): string {
                        if ($record->is_one_time) {
                            return $record->used_count >= 1 ? 'danger' : 'purple';
                        }
                        if ($record->max_uses === null) {
                            return 'info';
                        }

                        return $record->used_count >= $record->max_uses ? 'danger' : 'success';
                    }),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->state(function (SellerAccessCode $record): string {
                        if ($record->is_locked) {
                            return 'Terkunci (Lock)';
                        }
                        if (! $record->isAvailable()) {
                            return 'Kuota Habis';
                        }

                        return 'Aktif';
                    })
                    ->color(function (SellerAccessCode $record): string {
                        if ($record->is_locked) {
                            return 'danger';
                        }
                        if (! $record->isAvailable()) {
                            return 'warning';
                        }

                        return 'success';
                    }),
                TextColumn::make('used_at')
                    ->label('Terakhir Dipakai')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('-'),
                TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->placeholder('Admin WhiMarket'),
                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('reset_quota')
                    ->label('Reset Kuota')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Reset Kuota Kode Akses VIP')
                    ->modalDescription(fn (SellerAccessCode $record) => "Apakah Anda yakin ingin mereset kuota penggunaan kode '{$record->code}' kembali ke 0?")
                    ->action(function (SellerAccessCode $record) {
                        $record->resetQuota();
                        Notification::make()
                            ->title("Kuota kode '{$record->code}' berhasil direset ke 0!")
                            ->success()
                            ->send();
                    }),

                Action::make('toggle_lock')
                    ->label(fn (SellerAccessCode $record): string => $record->is_locked ? 'Buka Kunci' : 'Kunci (Lock)')
                    ->icon(fn (SellerAccessCode $record): string => $record->is_locked ? 'heroicon-o-lock-open' : 'heroicon-o-lock-closed')
                    ->color(fn (SellerAccessCode $record): string => $record->is_locked ? 'success' : 'danger')
                    ->action(function (SellerAccessCode $record) {
                        $record->update(['is_locked' => ! $record->is_locked]);
                        Notification::make()
                            ->title($record->is_locked ? "Kode '{$record->code}' Berhasil Dikunci!" : "Kode '{$record->code}' Dibuka Kembali!")
                            ->success()
                            ->send();
                    }),

                EditAction::make()
                    ->label('Ubah')
                    ->icon('heroicon-o-pencil-square'),

                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSellerAccessCodes::route('/'),
            'create' => CreateSellerAccessCode::route('/create'),
            'edit' => EditSellerAccessCode::route('/{record}/edit'),
        ];
    }
}
