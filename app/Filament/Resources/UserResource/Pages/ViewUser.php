<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('add-admin-rights')
                ->label('Erteile Admin Rechte')
                ->requiresConfirmation()
                ->action(fn () => $this->record->update(['is_admin' => true]))
                ->visible(fn () => ! $this->record->is_admin),

            Action::make('remove-admin-rights')
                ->label('Entferne Admin Rechte')
                ->requiresConfirmation()
                ->action(fn () => $this->record->update(['is_admin' => false]))
                ->visible(fn () => $this->record->is_admin),
        ];
    }
}
