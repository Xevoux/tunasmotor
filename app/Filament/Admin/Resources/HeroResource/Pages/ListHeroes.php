<?php

namespace App\Filament\Admin\Resources\HeroResource\Pages;

use App\Filament\Admin\Resources\HeroResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHeroes extends ListRecords
{
    protected static string $resource = HeroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
