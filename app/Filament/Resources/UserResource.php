<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\DticketRequestsRelationManager;
use App\Models\User;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'Account';

    protected static ?string $pluralModelLabel = 'Accounts';

    protected static ?string $navigationLabel = 'Accounts';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $navigationGroup = 'Accounts';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'btu_id'];
    }

    /**
     * Get the details for the global search result.
     *
     * @param  User  $record
     * @return array<string, string>
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Semesterticket' => $record->isDticketEligable() ? 'Berechtigt' : 'Nicht berechtigt',
            'Gültiger Name' => empty($record->firstname) || empty($record->lastname) ? 'Nein' : 'Ja',
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([

            Section::make()
                ->schema([

                    TextEntry::make('name'),

                    Grid::make()->schema([
                        TextEntry::make('firstname')
                            ->label('Vorname'),

                        TextEntry::make('lastname')
                            ->label('Nachname'),
                    ]),

                    TextEntry::make('email')
                        ->label('E-Mail'),

                    TextEntry::make('btu_id')
                        ->label('BTU-ID'),

                    TextEntry::make('entitlements')
                        ->listWithLineBreaks()
                        ->bulleted(),

                    TextEntry::make('is_admin')
                        ->label('Admin-Rechte')
                        ->badge()
                        ->formatStateUsing(fn (bool $state) => $state ? 'Hat Admin-Rechte' : 'Keine Admin-Rechte')
                        ->color(fn (bool $state): string => match ($state) {
                            true => 'success',
                            false => 'danger',
                        }),
                ]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('E-Mail')
                    ->searchable(),

                TextColumn::make('btu_id')
                    ->label('BTU-ID')
                    ->searchable(),

                TextColumn::make('is_admin')
                    ->label('Admin-Rechte')
                    ->badge()
                    ->formatStateUsing(fn (bool $state) => $state ? 'Admin' : 'Nicht-Admin')
                    ->color(fn (bool $state): string => match ($state) {
                        true => 'success',
                        false => 'danger',
                    }),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            DticketRequestsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
}
