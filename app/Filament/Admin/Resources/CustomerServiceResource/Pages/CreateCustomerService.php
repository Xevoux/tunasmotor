<?php

namespace App\Filament\Admin\Resources\CustomerServiceResource\Pages;

use App\Filament\Admin\Resources\CustomerServiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerService extends CreateRecord
{
    protected static string $resource = CustomerServiceResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

