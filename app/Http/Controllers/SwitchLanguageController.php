<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class SwitchLanguageController extends Controller
{
    public function __invoke()
    {
        Auth::user()->update([
            'locale' => App::getLocale() === 'de' ? 'en' : 'de',
        ]);

        return Redirect::route('dashboard');
    }
}
