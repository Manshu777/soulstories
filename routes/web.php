<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\PersonalAccessToken;

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('forgot.password');
Route::view('/verify-otp', 'auth.verify-otp');

// After API login: set web session from token so navbar shows auth links
Route::get('/auth/session-set', function (Request $request) {
    $token = $request->query('token');
    if (! $token) {
        return redirect()->route('login')->with('error', 'Missing token.');
    }
    $accessToken = PersonalAccessToken::findToken($token);
    if (! $accessToken) {
        return redirect()->route('login')->with('error', 'Invalid or expired token.');
    }
    $user = $accessToken->tokenable;
    Auth::login($user);
    $request->session()->regenerate();
    return redirect()->intended(route('diary.dashboard'));
})->name('auth.session-set');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('diary.home');
})->name('logout')->middleware('auth');

Route::get('/my-works/new', function () {
    return view('myworks.index');
})->name('myworks.index');

require __DIR__.'/diary.php';
require __DIR__.'/admin.php';