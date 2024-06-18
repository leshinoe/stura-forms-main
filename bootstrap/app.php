<?php

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\SetLocaleFromUser;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware
            ->web(
                append: [SetLocaleFromUser::class],
            )
            ->validateCsrfTokens(
                except: ['/auth/saml2/callback'],
            )
            ->redirectGuestsTo('/')
            ->alias([
                'lang' => SetLocale::class,
                'auth' => Authenticate::class,
            ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
