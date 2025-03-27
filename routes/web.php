<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Post\LocationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubjectController;

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    Route::get('/user-management', [UserController::class, 'index'])->name('user.management');

    Route::get('/users', [UserController::class, 'getUsers'])->name('users.list');

    Route::get('/resource-management', fn() => view('resource-management'))->name('resource.management');
    Route::get('/announcements', fn() => view('announcements'))->name('announcements');
    Route::get('/notifications', fn() => view('notifications'))->name('notifications');
    Route::get('/settings', fn() => view('settings'))->name('settings');
    Route::delete('/delete-user/{id}', [UserController::class, 'deleteUser']);
    Route::post('/change-status/{id}', [UserController::class, 'changeStatus']);
    // In routes/web.php
    Route::get('/location', fn() => view('location'))->name('location');

    Route::get('/locations', [LocationController::class, 'getLocations'])->name('get.locations');
    Route::post('/add-location', [LocationController::class, 'addLocation'])->name('add.location');
    Route::delete('/delete-location/{id}', [LocationController::class, 'deleteLocation']);
    Route::put('/update-location/{id}', [LocationController::class, 'updateLocation']);

    Route::get('/subjects', fn() => view('subjects'))->name('subjects');

    Route::get('/get-subjects', [SubjectController::class, 'getSubjects'])->name('get.subjects');
    Route::post('/add-subject', [SubjectController::class, 'addSubject'])->name('add.subject');
    Route::put('/update-subject/{id}', [SubjectController::class, 'updateSubject']);
    Route::delete('/delete-subject/{id}', [SubjectController::class, 'deleteSubject']);
});


// ✅ Login & Logout Routes
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// ✅ Authentication Views
Route::get('/sign-up', fn() => view('auth.sign-up'))->name('sign-up');
Route::get('/sign-in', fn() => view('auth.sign-in'))->name('sign-in');
Route::get('/forget-password', fn() => view('auth.forget-password'))->name('password.request');
