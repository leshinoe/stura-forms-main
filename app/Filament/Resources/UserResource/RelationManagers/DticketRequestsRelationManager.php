<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class DticketRequestsRelationManager extends RelationManager
{
    protected static string $relationship = 'dticketRequests';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('semester')
            ->heading('Anträge auf Befreiung vom Semesterticket')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->formatStateUsing(fn ($state) => "#{$state}"),

                Tables\Columns\TextColumn::make('semester')
                    ->label(__('stura.fields.semester')),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('stura.fields.created_at'))
                    ->formatStateUsing(fn ($state) => $state->format('d.m.Y')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->url(fn ($record) => route(
                    'filament.admin.resources.dticket-requests.view', $record
                ), true),
            ])
            ->bulkActions([
                //
            ]);
    }
}
