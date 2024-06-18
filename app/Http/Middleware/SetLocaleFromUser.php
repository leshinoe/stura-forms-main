<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {

            if (Auth::user()->locale === null && Session::has('default_locale')) {
                Auth::user()->update([
                    'locale' => Session::pull('default_locale'),
                ]);
            }

            App::setLocale(
                Auth::user()->locale ?? config('app.locale')
            );
        }

        return $next($request);
    }
}
