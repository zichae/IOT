<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FormController;
use App\Http\Controllers\Api\UserController;

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::get('/google', [AuthController::class, 'redirectToGoogle'])->middleware('guest')->name('google.login');
    Route::get('/google/callback', [AuthController::class, 'handleGoogleCallback'])->middleware('guest')->name('google.callback');
    Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->name('login');
    Route::post('/register', [AuthController::class, 'register'])->middleware('guest')->name('register');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('user')->name('logout');
});

Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => 'user'], function () {
    Route::get('/profile', [UserController::class, 'getUserProfile'])->name('profile');
    Route::post('/update', [UserController::class, 'update'])->name('update');
});

Route::group(['prefix' => 'form', 'as' => 'form.'], function () {
    Route::group(['prefix' => 'form', 'as' => 'form.', 'middleware' => 'admin'], function () {
        Route::get('/', [FormController::class, 'index']);
        Route::get('/{id}', [FormController::class, 'show']);
    });
    Route::group(['prefix' => 'form', 'as' => 'form.', 'middleware' => 'user'], function () {
        Route::post('/create', [FormController::class, 'store']);
    });
});