<?php

namespace App\Filament\Resources\DticketExcludeResource\Pages;

use App\Filament\Resources\DticketExcludeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDticketExclude extends EditRecord
{
    protected static string $resource = DticketExcludeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
