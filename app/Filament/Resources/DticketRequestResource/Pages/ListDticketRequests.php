<?php

namespace App\Filament\Resources\DticketRequestResource\Pages;

use App\Filament\Resources\DticketRequestResource;
use App\Models\Dticket\DticketRequest;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListDticketRequests extends ListRecords
{
    protected static string $resource = DticketRequestResource::class;

    public function getDefaultActiveTab(): string|int|null
    {
        return 'pending';
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Alle')
                ->badge(DticketRequest::query()->count()),

            'pending' => Tab::make('Zu Bearbeiten')
                ->badge(DticketRequest::query()->where('status', 'pending')->count())
                ->modifyQueryUsing(
                    fn (Builder $query) => $query->where('status', 'pending')
                ),

            'approved' => Tab::make('Angenommen')
                ->badge(DticketRequest::query()->where('status', 'approved')->count())
                ->modifyQueryUsing(
                    fn (Builder $query) => $query->where('status', 'approved')
                ),

            'paid' => Tab::make('Ausgezahlt')
                ->badge(DticketRequest::query()->where('status', 'paid')->count())
                ->modifyQueryUsing(
                    fn (Builder $query) => $query->where('status', 'paid')
                ),

            'rejected' => Tab::make('Abgelehnt')
                ->badge(DticketRequest::query()->where('status', 'rejected')->count())
                ->modifyQueryUsing(
                    fn (Builder $query) => $query->where('status', 'rejected')
                ),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
