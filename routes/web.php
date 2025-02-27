<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\TwoFactorController;

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/user-management', function () {
    return view('user-management');
})->name('user.management');

Route::get('/resource-management', function () {
    return view('resource-management');
})->name('resource.management');

Route::get('/announcements', function () {
    return view('announcements');
})->name('announcements');

Route::get('/notifications', function () {
    return view('notifications');
})->name('notifications');

Route::get('/settings', function () {
    return view('settings');
})->name('settings');


// register
Route::get('/sign-up', function () {
    return view('auth.sign-up');
})->name('sign-up');

Route::get('/sign-in', function () {
    return view('auth.sign-in');
})->name('sign-in');

// Route::get('/forget-password', function () {
//     return view('auth.forget-password');
// })->name('password.request');


Route::get('/forget-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forget-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/two-factor-authentication', [TwoFactorController::class, 'show2FAForm'])->name('two-factor');
Route::post('/two-factor-authentication', [TwoFactorController::class, 'verify2FA']);
