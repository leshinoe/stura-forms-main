<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/', function (Request $request) {

    Auth::loginUsingId($request->input('user_id'));
    Session::regenerate();

    return redirect()->intended('/');
});
