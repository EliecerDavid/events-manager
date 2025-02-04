<?php

use Illuminate\Support\Facades\Route;

Route::post('/login', \App\Http\Controllers\Users\LoginController::class);
Route::post('/register', \App\Http\Controllers\Users\CreateController::class);

Route::middleware('auth:sanctum')
    ->group(function () {
        Route::get('/user', \App\Http\Controllers\Users\CurrentUserController::class);

        Route::get('/users', \App\Http\Controllers\Users\IndexController::class);

        Route::get('/events', \App\Http\Controllers\Events\IndexController::class);
        Route::post('/events', \App\Http\Controllers\Events\CreateController::class);

        Route::get('/events/{event}/participants', \App\Http\Controllers\Events\IndexParticipantsController::class);
        Route::post('/events/{event}/participants', \App\Http\Controllers\Events\AddParticipantsController::class);
    });
