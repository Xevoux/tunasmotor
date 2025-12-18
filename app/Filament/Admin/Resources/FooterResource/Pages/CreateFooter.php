<?php

namespace App\Filament\Admin\Resources\FooterResource\Pages;

use App\Filament\Admin\Resources\FooterResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFooter extends CreateRecord
{
    protected static string $resource = FooterResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
