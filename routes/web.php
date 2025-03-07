<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\UserController;

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    Route::get('/user-management', [UserController::class, 'index'])->name('user.management');

    Route::get('/users', [UserController::class, 'getUsers'])->name('users.list');

    Route::get('/resource-management', fn() => view('resource-management'))->name('resource.management');
    Route::get('/announcements', fn() => view('announcements'))->name('announcements');
    Route::get('/notifications', fn() => view('notifications'))->name('notifications');
    Route::get('/settings', fn() => view('settings'))->name('settings');
});

// ✅ Login & Logout Routes
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// ✅ Authentication Views
Route::get('/sign-up', fn() => view('auth.sign-up'))->name('sign-up');
Route::get('/sign-in', fn() => view('auth.sign-in'))->name('sign-in');
Route::get('/forget-password', fn() => view('auth.forget-password'))->name('password.request');
