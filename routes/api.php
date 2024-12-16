<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
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



Route::group(['middleware' => 'auth'], function () {
    Route::get('/me', ProfileController::class)->name('me');

    Route::post('/tasks', \App\Http\Controllers\Task\CreateTaskController::class)->name('tasks.create');
});
