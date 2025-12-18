<?php

namespace App\Filament\Admin\Resources\HeroResource\Pages;

use App\Filament\Admin\Resources\HeroResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateHero extends CreateRecord
{
    protected static string $resource = HeroResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
