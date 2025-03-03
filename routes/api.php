<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
// use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

Route::post('/register', [RegisterController::class, 'register'])->name('api.register');
Route::post('/login', [LoginController::class, 'login'])->name('api.login');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum')->name('api.logout');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetOTP'])->name('api.forgot-password');
// Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->name('api.reset-password');

Route::post('/app-login', [LoginController::class, 'appLogin'])->name('api.app-login');
Route::post('/verify-otp', [LoginController::class, 'verifyOTP'])->name('api.verify-otp');
