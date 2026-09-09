<?php

namespace App\Filament\Resources\SellerAccessCodes\Pages;

use App\Filament\Resources\SellerAccessCodes\SellerAccessCodeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSellerAccessCodes extends ListRecords
{
    protected static string $resource = SellerAccessCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('+ Generate Kode VIP Baru'),
        ];
    }
}
