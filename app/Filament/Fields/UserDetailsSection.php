<?php

namespace App\Filament\Fields;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Auth;

class UserDetailsSection
{
    public static function make(): Section
    {
        return Section::make(__('stura.dticket.about.title'))
            ->description(__('stura.dticket.about.description'))
            ->schema([
                Placeholder::make('name')
                    ->label(__('stura.fields.name'))
                    ->content(fn () => Auth::user()->name),

                Placeholder::make('email')
                    ->label(__('stura.fields.email'))
                    ->content(fn () => Auth::user()->email),
            ]);
    }
}
