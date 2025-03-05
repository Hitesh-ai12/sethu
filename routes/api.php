<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

Route::post('/register', [RegisterController::class, 'register'])->name('api.register');
Route::post('/login', [LoginController::class, 'apilogin']);

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetOTP']);
Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOTP']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/update-profile', [RegisterController::class, 'updateProfile']);
    Route::put('/change-city', [RegisterController::class, 'changeCity']);
    Route::put('/personalize-skills', [RegisterController::class, 'personalizeSkills']);
    Route::post('/logout', [LoginController::class, 'logoutapi']);

});


