<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\User\ProfileController;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterController::class)->name('register');
Route::post('/login', LoginController::class)->name('login');

Route::group([
    'middleware' => ['api'],
    'prefix' => 'auth',
], function () {
    Route::post('/logout', LogoutController::class)->name('logout');
});

Route::get('/me', ProfileController::class)
    ->middleware('auth')
    ->name('protected');
