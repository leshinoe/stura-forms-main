<?php

namespace App\Filament\Fields;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;

class BankingAccountSection
{
    public static function make(): Section
    {
        return Section::make(__('stura.dticket.banking.title'))
            ->schema([

                TextInput::make('banking_name')
                    ->label(__('stura.fields.banking_name'))
                    ->maxLength('255')
                    ->required(),

                TextInput::make('banking_iban')
                    ->label(__('stura.fields.banking_iban'))
                    ->required()
                    ->string()
                    ->maxLength('255')
                    ->rules(['iban']),

                TextInput::make('banking_bic')
                    ->label(__('stura.fields.banking_bic'))
                    ->required()
                    ->string()
                    ->maxLength('255')
                    ->rules(['bic']),
            ]);
    }
}
