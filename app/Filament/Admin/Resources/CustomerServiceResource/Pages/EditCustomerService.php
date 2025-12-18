<?php

namespace App\Filament\Admin\Resources\CustomerServiceResource\Pages;

use App\Filament\Admin\Resources\CustomerServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomerService extends EditRecord
{
    protected static string $resource = CustomerServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

