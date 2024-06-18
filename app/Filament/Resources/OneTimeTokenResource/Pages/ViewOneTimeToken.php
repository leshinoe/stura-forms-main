<?php

namespace App\Filament\Resources\OneTimeTokenResource\Pages;

use App\Filament\Resources\OneTimeTokenResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOneTimeToken extends ViewRecord
{
    protected static string $resource = OneTimeTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
