<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OneTimeTokenResource\Pages;
use App\Models\OneTimeToken;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OneTimeTokenResource extends Resource
{
    protected static ?string $model = OneTimeToken::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $modelLabel = 'Anmeldelink';

    protected static ?string $pluralModelLabel = 'Anmeldelinks';

    protected static ?string $navigationLabel = 'Anmeldelinks';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $navigationGroup = 'Accounts';

    protected static ?int $navigationSort = 1;

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([

            InfolistSection::make()
                ->schema([

                    TextEntry::make('user.name')->label('Studierender'),

                    TextEntry::make('user.email')->label('E-Mail'),

                    TextEntry::make('expires_at')->label('Gültig bis')
                        ->formatStateUsing(function (OneTimeToken $record) {
                            return $record->expires_at->format('d.m.Y');
                        }),

                    TextEntry::make('token')
                        ->label('Einmallink')
                        ->formatStateUsing(function (OneTimeToken $record) {
                            return route('auth.token', [
                                'token' => $record->token,
                            ]);
                        }),
                ]),

        ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([

                TextInput::make('name')
                    ->label('Name')
                    ->required(),

                TextInput::make('email')
                    ->label('E-Mail')
                    ->notRegex('/.+\@b\-tu\.de/')
                    ->required()
                    ->email()
                    ->validationMessages([
                        'not_regex' => 'Anmeldelinks können nur für externe E-Mail-Adressen erstellt werden und nicht für BTU Accounts / E-Mail-Adressen.',
                    ]),

                DatePicker::make('expires_at')
                    ->label('Gültig bis')
                    ->native(false)
                    ->default(now()->addDays(14))
                    ->displayFormat('d.m.Y')
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Studierender'),

                TextColumn::make('user.email')->label('E-Mail'),

                TextColumn::make('expires_at')->label('Gültig bis')
                    ->formatStateUsing(function (OneTimeToken $record) {
                        return $record->expires_at->format('d.m.Y');
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListOneTimeTokens::route('/'),
            'create' => Pages\CreateOneTimeToken::route('/create'),
            'view' => Pages\ViewOneTimeToken::route('/{record}'),
        ];
    }
}
