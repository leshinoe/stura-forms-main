<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DticketRequestResource\Pages;
use App\Filament\Resources\DticketRequestResource\Schemas\DticketRequestInfolist;
use App\Models\Dticket\DticketConfiguration;
use App\Models\Dticket\DticketRequest;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class DticketRequestResource extends Resource
{
    protected static ?string $model = DticketRequest::class;

    protected static ?string $modelLabel = 'Antrag auf Befreiung vom Semesterticket';

    protected static ?string $pluralModelLabel = 'Anträge auf Befreiung vom Semesterticket';

    protected static ?string $navigationLabel = 'Anträge auf Befreiung';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $recordTitleAttribute = 'user.name';

    protected static ?string $navigationGroup = 'Semesterticket';

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return 'SemTix Antrag - '.$record->user->name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Semester' => $record->semester,
            'Eingereicht' => $record->created_at->format('d.m.Y'),
            'Status' => __('stura.dticket.status.'.$record->status),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['user']);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema(DticketRequestInfolist::schema($infolist));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('user.name')
                    ->label('Studierender')
                    ->searchable(),

                TextColumn::make('semester')
                    ->label('Semester'),

                TextColumn::make('created_at')
                    ->label('Eingereicht')
                    ->date('d.m.Y'),

                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (DticketRequest $record) => $record->statusLabel())
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        'approved' => 'success',
                        'paid' => 'success',
                    }),

            ])
            ->filters([
                SelectFilter::make('semester')
                    ->options(
                        fn () => DticketConfiguration::pluck('semester', 'semester')
                    )
                    ->attribute('semester'),
            ])
            ->filtersTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                BulkAction::make('mark_as_paid')
                    ->label('Als ausgezahlt markieren')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (Collection $records) => $records->each->update(['status' => 'paid']))
                    ->deselectRecordsAfterCompletion(),
            ])
            ->checkIfRecordIsSelectableUsing(
                fn (DticketRequest $record): bool => $record->status === 'approved',
            );
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDticketRequests::route('/'),
            'view' => Pages\ViewDticketRequest::route('/{record}'),
        ];
    }
}
