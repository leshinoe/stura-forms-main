<?php

namespace App\Filament\Resources\DticketConfigurationResource\Pages;

use App\Filament\Resources\DticketConfigurationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDticketConfigurations extends ListRecords
{
    protected static string $resource = DticketConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
