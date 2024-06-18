<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DticketExcludeResource\Pages;
use App\Models\Dticket\DticketExclude;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DticketExcludeResource extends Resource
{
    protected static ?string $model = DticketExclude::class;

    protected static ?string $modelLabel = 'Manuell Exkludiert';

    protected static ?string $pluralModelLabel = 'Manuell Exkludierte';

    protected static ?string $navigationLabel = 'Manuell Exkludiert';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Semesterticket';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        $month = today()->month;
        $year = today()->year;

        $endOfSemester = match (true) {
            $month >= 10 => Carbon::create($year + 1, 3, 31),
            $month <= 3 => Carbon::create($year, 3, 31),
            default => Carbon::create($year, 9, 30),
        };

        return $form
            ->schema([

                TextInput::make('name')
                    ->label('Name')
                    ->required(),

                TextInput::make('btu_id')
                    ->label('BTU-ID')
                    ->required(),

                DatePicker::make('exclude_starts_at')
                    ->label('Ausschluss beginnt am')
                    ->default(today())
                    ->native(false)
                    ->displayFormat('d.m.Y')
                    ->required(),

                DatePicker::make('exclude_ends_at')
                    ->label('Ausschluss endet am')
                    ->default($endOfSemester)
                    ->native(false)
                    ->displayFormat('d.m.Y')
                    ->required(),

                Textarea::make('reason')
                    ->label('Grund')
                    ->columnSpanFull()
                    ->rows(5)
                    ->nullable(),

                Toggle::make('is_active')
                    ->label('Aktiv')
                    ->default(true)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('btu_id')
                    ->label('BTU-ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('exclude_starts_at')
                    ->label('Beginn')
                    ->formatStateUsing(function (Carbon $state) {
                        return $state->format('d.m.Y');
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('exclude_ends_at')
                    ->label('Ende')
                    ->formatStateUsing(function (Carbon $state) {
                        return $state->format('d.m.Y');
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('is_active')
                    ->badge()
                    ->label('Status')
                    ->formatStateUsing(fn (bool $state) => $state ? 'Aktiv' : 'Inaktiv')
                    ->color(fn (bool $state) => match ($state) {
                        true => 'success',
                        false => 'danger',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
            ]);
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
            'index' => Pages\ListDticketExcludes::route('/'),
            'create' => Pages\CreateDticketExclude::route('/create'),
            'edit' => Pages\EditDticketExclude::route('/{record}/edit'),
        ];
    }
}
