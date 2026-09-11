<?php

namespace App\Filament\Resources\SellerAccessCodes;

use App\Filament\Resources\SellerAccessCodes\Pages\CreateSellerAccessCode;
use App\Filament\Resources\SellerAccessCodes\Pages\EditSellerAccessCode;
use App\Filament\Resources\SellerAccessCodes\Pages\ListSellerAccessCodes;
use App\Models\SellerAccessCode;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
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

    protected static ?string $modelLabel = 'Kode Akses VIP';

    protected static ?string $pluralModelLabel = 'Manajemen Kode Akses VIP Seller';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informasi & Konfigurasi Kode Akses')
                ->description('Tentukan format kode registrasi, batas kuota pemakaian, dan target email calon seller.')
                ->icon('heroicon-o-key')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('code')
                            ->label('Kode Akses VIP')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->default(fn () => 'WHI-VIP-'.strtoupper(Str::random(6)))
                            ->helperText('Kode unik aktivasi pendaftaran seller VIP.')
                            ->prefixIcon('heroicon-m-key')
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
                            ->prefixIcon('heroicon-m-envelope')
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
                        ->prefixIcon('heroicon-m-ticket')
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
            ->recordActionsColumnLabel('Aksi')
            ->recordActionsAlignment('center')
            ->columns([
                TextColumn::make('code')
                    ->label('Kode Akses VIP')
                    ->fontFamily('mono')
                    ->weight('bold')
                    ->icon('heroicon-m-key')
                    ->color('primary')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Kode VIP berhasil disalin ke clipboard!')
                    ->description(function (SellerAccessCode $record): string {
                        if ($record->is_one_time) {
                            return 'Eksklusif 1x Pakai';
                        }
                        if ($record->isUnlimited()) {
                            return 'Multi-User (Tanpa Batas)';
                        }

                        return 'Kuota Maksimal '.$record->max_uses.' Toko';
                    }),

                TextColumn::make('email')
                    ->label('Target Seller')
                    ->icon(fn (SellerAccessCode $record): string => filled($record->email) ? 'heroicon-m-envelope' : 'heroicon-m-globe-alt')
                    ->color(fn (SellerAccessCode $record): string => filled($record->email) ? 'info' : 'gray')
                    ->state(fn (SellerAccessCode $record): string => filled($record->email) ? $record->email : 'Publik (Siapapun)')
                    ->description(fn (SellerAccessCode $record): string => filled($record->email) ? 'Khusus email terdaftar' : 'Bisa dipakai siapa saja')
                    ->searchable(),

                TextColumn::make('usage')
                    ->label('Kuota Penggunaan')
                    ->badge()
                    ->icon('heroicon-m-ticket')
                    ->state(function (SellerAccessCode $record): string {
                        if ($record->is_one_time) {
                            return $record->used_count >= 1 ? '1x Pakai (1/1 Terpakai)' : '1x Pakai (0/1 Siap Pakai)';
                        }
                        if ($record->max_uses === null) {
                            return $record->used_count.' Toko (Tanpa Batas)';
                        }

                        $percentage = $record->max_uses > 0 ? round(($record->used_count / $record->max_uses) * 100) : 0;

                        return "{$record->used_count} / {$record->max_uses} ({$percentage}%)";
                    })
                    ->color(function (SellerAccessCode $record): string {
                        if ($record->is_one_time) {
                            return $record->used_count >= 1 ? 'danger' : 'primary';
                        }
                        if ($record->max_uses === null) {
                            return 'info';
                        }

                        if ($record->used_count >= $record->max_uses) {
                            return 'danger';
                        }
                        if ($record->used_count >= ($record->max_uses * 0.7)) {
                            return 'warning';
                        }

                        return 'success';
                    }),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->icon(function (SellerAccessCode $record): string {
                        if ($record->is_locked) {
                            return 'heroicon-m-lock-closed';
                        }
                        if (! $record->isAvailable()) {
                            return 'heroicon-m-x-circle';
                        }

                        return 'heroicon-m-check-circle';
                    })
                    ->state(function (SellerAccessCode $record): string {
                        if ($record->is_locked) {
                            return 'Terkunci';
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
                    ->description(fn (SellerAccessCode $record) => $record->used_at?->diffForHumans() ?? 'Belum pernah')
                    ->placeholder('Belum pernah'),

                TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->icon('heroicon-m-user')
                    ->default('Admin WhiMarket')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y')
                    ->description(fn (SellerAccessCode $record) => ($record->created_at?->diffForHumans() ?? '').' • '.($record->creator?->name ?? 'Admin'))
                    ->sortable(),
            ])
            ->emptyStateHeading('Belum Ada Kode Akses VIP')
            ->emptyStateDescription('Buat kode registrasi eksklusif pertama untuk mengundang creator dan seller ke WhiMarket.')
            ->emptyStateIcon('heroicon-o-key')
            ->recordActions([
                Action::make('toggle_lock')
                    ->label(fn (SellerAccessCode $record): string => $record->is_locked ? 'Buka Kunci' : 'Kunci')
                    ->icon(fn (SellerAccessCode $record): string => $record->is_locked ? 'heroicon-o-lock-open' : 'heroicon-o-lock-closed')
                    ->iconButton()
                    ->color(fn (SellerAccessCode $record): string => $record->is_locked ? 'success' : 'danger')
                    ->tooltip(fn (SellerAccessCode $record): string => $record->is_locked ? 'Buka Kunci Kode Akses' : 'Kunci Kode Akses (Nonaktifkan)')
                    ->requiresConfirmation()
                    ->modalHeading(fn (SellerAccessCode $record) => $record->is_locked ? "Buka Kunci Kode '{$record->code}'" : "Kunci Kode '{$record->code}'")
                    ->modalDescription(fn (SellerAccessCode $record) => $record->is_locked ? 'Kode ini akan diaktifkan kembali dan dapat digunakan calon seller untuk mendaftar.' : 'Kode ini akan dinonaktifkan sementara dan tidak dapat digunakan registrasi.')
                    ->action(function (SellerAccessCode $record) {
                        $record->update(['is_locked' => ! $record->is_locked]);
                        Notification::make()
                            ->title($record->is_locked ? "Kode '{$record->code}' Berhasil Dikunci!" : "Kode '{$record->code}' Dibuka Kembali!")
                            ->success()
                            ->send();
                    }),

                ActionGroup::make([
                    Action::make('copy_code')
                        ->label('Salin Kode VIP')
                        ->icon('heroicon-o-clipboard-document')
                        ->color('gray')
                        ->action(function (SellerAccessCode $record) {
                            Notification::make()
                                ->title("Kode VIP '{$record->code}' siap disalin!")
                                ->success()
                                ->send();
                        }),

                    Action::make('reset_quota')
                        ->label('Reset Kuota Pemakaian')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->visible(fn (SellerAccessCode $record) => $record->used_count > 0 || $record->is_locked)
                        ->requiresConfirmation()
                        ->modalHeading('Reset Kuota Kode Akses VIP')
                        ->modalDescription(fn (SellerAccessCode $record) => "Apakah Anda yakin ingin mereset riwayat pemakaian kode '{$record->code}' kembali ke 0?")
                        ->action(function (SellerAccessCode $record) {
                            $record->resetQuota();
                            Notification::make()
                                ->title("Kuota kode '{$record->code}' berhasil direset ke 0!")
                                ->success()
                                ->send();
                        }),

                    EditAction::make()
                        ->label('Ubah Kode')
                        ->icon('heroicon-o-pencil-square'),

                    DeleteAction::make()
                        ->label('Hapus Kode')
                        ->icon('heroicon-o-trash'),
                ])
                    ->iconButton()
                    ->color('gray')
                    ->tooltip('Opsi Tindakan Lainnya'),
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
