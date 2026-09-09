<?php

namespace App\Filament\Resources\SellerAccessCodes\Pages;

use App\Filament\Resources\SellerAccessCodes\SellerAccessCodeResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateSellerAccessCode extends CreateRecord
{
    protected static string $resource = SellerAccessCodeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();

        $quotaType = $data['quota_type'] ?? 'limited';

        if ($quotaType === 'one_time') {
            $data['max_uses'] = 1;
            $data['is_one_time'] = true;
        } elseif ($quotaType === 'unlimited') {
            $data['max_uses'] = null;
            $data['is_one_time'] = false;
        } else {
            $data['is_one_time'] = false;
            $data['max_uses'] = ! empty($data['max_uses']) ? (int) $data['max_uses'] : 10;
        }

        unset($data['quota_type']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
