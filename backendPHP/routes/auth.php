<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Laravel\Socialite\Facades\Socialite;

Route::middleware('guest')->group(function () {
    Volt::route('login', 'auth.login')
        ->name('login');

    Volt::route('register', 'auth.register')
        ->name('register');

    Volt::route('forgot-password', 'auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'auth.reset-password')
        ->name('password.reset');

});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'auth.confirm-password')
        ->name('password.confirm');
});

Route::get('/auth/redirect', function () {
    return Socialite::driver('google')->redirect();
})
    ->name('google.redirect');
 
Route::get('/auth/callback', function () {
    $googleuser = Socialite::driver('google')->stateless()->user();
 
    // $user->token
    $user = User::updateOrCreate([
        'google_id' => $googleuser->id,
    ], [
	'google_id' => $googleuser->id,
        'name' => $googleuser->name,
        'email' => $googleuser->email,
        'password' => Hash::make(Str::random(24)),
    ]);
 
    Auth::login($user);
 
    return redirect('/dashboard');
});

Route::post('logout', App\Livewire\Actions\Logout::class)
    ->name('logout');
