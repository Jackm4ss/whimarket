<?php

namespace App\Filament\Resources\SellerAccessCodes;

use App\Filament\Resources\SellerAccessCodes\Pages\ManageSellerAccessCodes;
use App\Models\SellerAccessCode;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SellerAccessCodeResource extends Resource
{
    protected static ?string $model = SellerAccessCode::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    protected static ?string $navigationLabel = 'Kode Akses VIP Seller';

    protected static ?string $modelLabel = 'Kode Akses';

    protected static ?string $pluralModelLabel = 'Manajemen Kode Akses VIP Seller';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode Akses VIP')
                    ->fontFamily('mono')
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email Calon Seller')
                    ->placeholder('Siapapun (Bebas)')
                    ->searchable(),
                IconColumn::make('is_used')
                    ->label('Terpakai')
                    ->boolean(),
                TextColumn::make('used_at')
                    ->label('Waktu Aktivasi')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('-'),
                TextColumn::make('creator.name')
                    ->label('Dibuat Oleh Admin')
                    ->placeholder('-'),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->toolbarActions([
                Action::make('generate_code')
                    ->label('+ Generate Kode VIP Baru')
                    ->color('primary')
                    ->form([
                        TextInput::make('email')
                            ->label('Email Calon Seller (Opsional)')
                            ->email()
                            ->placeholder('Kosongkan jika kode bisa dipakai email apa saja'),
                    ])
                    ->action(function (array $data) {
                        $code = 'WHI-VIP-'.strtoupper(Str::random(6));
                        SellerAccessCode::create([
                            'code' => $code,
                            'email' => $data['email'] ?? null,
                            'is_used' => false,
                            'created_by' => Auth::id(),
                        ]);

                        Notification::make()
                            ->title("Kode VIP '{$code}' Berhasil Dibuat!")
                            ->success()
                            ->send();
                    }),
            ])
            ->recordActions([
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSellerAccessCodes::route('/'),
        ];
    }
}
