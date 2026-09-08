<?php

namespace App\Filament\Resources\SellerAccessCodes\Pages;

use App\Filament\Resources\SellerAccessCodes\SellerAccessCodeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSellerAccessCodes extends ManageRecords
{
    protected static string $resource = SellerAccessCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
