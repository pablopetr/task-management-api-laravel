<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Middleware\CheckIsAdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterController::class)->name('register');
Route::post('/login', LoginController::class)->name('login');

Route::group([
    'middleware' => ['api'],
    'prefix' => 'auth',
], function () {
    Route::post('/logout', LogoutController::class)->name('logout');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/me', ProfileController::class)->name('me');

    Route::apiResource('tasks', TaskController::class)
        ->middleware(CheckIsAdminMiddleware::class);
});
