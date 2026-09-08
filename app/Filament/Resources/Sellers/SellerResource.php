<?php

namespace App\Filament\Resources\Sellers;

use App\Enums\SellerStatus;
use App\Filament\Resources\Sellers\Pages\ManageSellers;
use App\Models\Seller;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SellerResource extends Resource
{
    protected static ?string $model = Seller::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static ?string $navigationLabel = 'Manajemen Toko Seller';

    protected static ?string $modelLabel = 'Toko Seller';

    protected static ?string $pluralModelLabel = 'Daftar Toko & Seller';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('store_name')
                    ->label('Nama Toko')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('username')
                    ->label('Username')
                    ->prefix('@')
                    ->searchable(),
                TextColumn::make('user.email')
                    ->label('Email Pemilik')
                    ->searchable(),
                TextColumn::make('bank_name')
                    ->label('Bank'),
                TextColumn::make('bank_account_number')
                    ->label('No. Rekening'),
                TextColumn::make('bank_account_name')
                    ->label('Nama Pemilik Rekening'),
                TextColumn::make('status')
                    ->label('Status Toko')
                    ->badge()
                    ->color(fn (SellerStatus $state): string => match ($state) {
                        SellerStatus::VERIFIED => 'success',
                        SellerStatus::PENDING => 'warning',
                        SellerStatus::REJECTED => 'danger',
                        SellerStatus::SUSPENDED => 'gray',
                    }),
                TextColumn::make('verified_at')
                    ->label('Terverifikasi')
                    ->dateTime('d M Y')
                    ->placeholder('-'),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Verifikasi Toko')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (Seller $record): bool => $record->status !== SellerStatus::VERIFIED)
                    ->action(function (Seller $record) {
                        $record->update([
                            'status' => SellerStatus::VERIFIED,
                            'verified_at' => now(),
                            'rejection_reason' => null,
                        ]);
                        Notification::make()->title('Toko Berhasil Diverifikasi!')->success()->send();
                    }),

                Action::make('reject')
                    ->label('Tolak Toko')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Seller $record): bool => $record->status === SellerStatus::PENDING)
                    ->form([
                        Textarea::make('rejection_reason')->label('Alasan Penolakan')->required(),
                    ])
                    ->action(function (Seller $record, array $data) {
                        $record->update([
                            'status' => SellerStatus::REJECTED,
                            'rejection_reason' => $data['rejection_reason'],
                        ]);
                        Notification::make()->title('Toko Ditolak')->danger()->send();
                    }),

                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSellers::route('/'),
        ];
    }
}
