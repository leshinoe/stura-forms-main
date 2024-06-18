<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\Saml2Controller;
use App\Http\Controllers\Auth\TokenController;
use App\Http\Controllers\SwitchLanguageController;
use App\Livewire\Dticket\SubmitDticketRequest;
use App\Livewire\Dticket\ViewSubmittedDticketRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/**
 * Public Routes
 */
Route::middleware(['guest'])->group(function () {

    Route::name('welcome')->middleware('lang:de')->get(
        '/',
        fn () => view('welcome')
    );

    Route::name('welcome.en')->middleware('lang:en')->get(
        '/en',
        fn () => view('welcome')
    );

});

/**
 * Protected Routes
 */
Route::bind('dticket_request', function (string $value) {
    return Auth::user()->dticketRequests()->findOrFail($value);
});

Route::name('attachments.show')->get(
    '/attachments/{userId}/{filename}',
    AttachmentController::class,
);

Route::middleware(['auth'])->group(function () {

    Route::name('dashboard')->get(
        '/dashboard',
        fn () => view('dashboard')
    );

    Route::name('requests.dticket')->get(
        '/requests/dticket',
        SubmitDticketRequest::class,
    );

    Route::name('requests.dticket.show')->get(
        '/requests/dticket/{dticket_request}',
        ViewSubmittedDticketRequest::class,
    );

    Route::name('locale')->post(
        '/locale',
        SwitchLanguageController::class
    );

});

/**
 * Authentication Routes
 */
Route::name('auth.saml2.metadata')->get(
    '/auth/saml2',
    [Saml2Controller::class, 'metadata']
);

Route::name('auth.logout')->middleware(['auth'])->delete(
    '/auth/logout',
    [LogoutController::class, 'logout']
);

Route::middleware(['guest', 'throttle:auth'])->group(function () {

    Route::name('auth.saml2.redirect')->get(
        '/auth/saml2/redirect',
        [Saml2Controller::class, 'redirect']
    );

    Route::name('auth.saml2.callback')->match(
        ['get', 'post'],
        '/auth/saml2/callback',
        [Saml2Controller::class, 'callback']
    );

    Route::name('auth.token')->get(
        '/auth/token/{token}',
        [TokenController::class, 'login']
    );

});
