<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DticketConfigurationResource\Pages;
use App\Models\Dticket\DticketConfiguration;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DticketConfigurationResource extends Resource
{
    protected static ?string $model = DticketConfiguration::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $modelLabel = 'Einstellungen für das Semesterticket';

    protected static ?string $pluralModelLabel = 'Einstellungen für das Semesterticket';

    protected static ?string $navigationLabel = 'Einstellungen';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $recordTitleAttribute = 'semester';

    protected static ?string $navigationGroup = 'Semesterticket';

    protected static ?int $navigationSort = 2;

    public static function nextSemesterToConfigure()
    {
        $latestConfiguration = DticketConfiguration::query()
            ->select('semester')
            ->latest('created_at')
            ->first();

        $semester = $latestConfiguration->semester;

        $isWiSe = str_starts_with($semester, 'WiSe');

        $nextSemester = $isWiSe ? 'SoSe' : 'WiSe';

        $year = substr($semester, 5, 4);
        $nextYear = strval(intval($year) + 1);

        if ($isWiSe) {
            $year = $nextYear;
        } else {
            $year .= '/'.$nextYear;
        }

        return $nextSemester.' '.$year;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([

                Forms\Components\TextInput::make('semester')
                    ->label(__('stura.fields.semester'))
                    ->default(static::nextSemesterToConfigure())
                    ->required(),

                Repeater::make('reasons_for_exemption')
                    ->columns(2)
                    ->schema([

                        Forms\Components\TextInput::make('key')
                            ->label('Key (Internal)')
                            ->columnSpanFull()
                            ->required(),

                        Forms\Components\Textarea::make('title_de')
                            ->label('Titel (DE)')
                            ->rows(3)
                            ->required(),

                        Forms\Components\Textarea::make('title_en')
                            ->label('Titel (EN)')
                            ->rows(3)
                            ->required(),

                        Forms\Components\Textarea::make('description_de')
                            ->label('Beschreibung (DE)')
                            ->rows(3)
                            ->required(),

                        Forms\Components\Textarea::make('description_en')
                            ->label('Beschreibung (EN)')
                            ->rows(3)
                            ->required(),
                    ])
                    ->columns(2),

                Repeater::make('reasons_for_rejection')
                    ->columns(2)
                    ->schema([

                        Forms\Components\TextInput::make('key')
                            ->label('Key (Internal)')
                            ->columnSpanFull()
                            ->required(),

                        Forms\Components\Textarea::make('de')
                            ->label('Beschreibung (DE)')
                            ->rows(5)
                            ->required(),

                        Forms\Components\Textarea::make('en')
                            ->label('Beschreibung (EN)')
                            ->rows(5)
                            ->required(),
                    ])
                    ->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('semester')
                    ->label(__('stura.fields.semester')),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ReplicateAction::make()
                    ->beforeReplicaSaved(function (DticketConfiguration $replica) {
                        $replica->semester = static::nextSemesterToConfigure();
                    }),
            ])
            ->bulkActions([

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
            'index' => Pages\ListDticketConfigurations::route('/'),
            'create' => Pages\CreateDticketConfiguration::route('/create'),
            'edit' => Pages\EditDticketConfiguration::route('/{record}/edit'),
        ];
    }
}
