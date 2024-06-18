<?php

namespace App\Filament\Resources\OneTimeTokenResource\Pages;

use App\Filament\Resources\OneTimeTokenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOneTimeTokens extends ListRecords
{
    protected static string $resource = OneTimeTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
