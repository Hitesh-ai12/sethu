<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
// use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

Route::post('/register', [RegisterController::class, 'register'])->name('api.register');


Route::post('/login', [LoginController::class, 'apilogin']);
Route::post('/forgot-password', [RegisterController::class, 'sendResetOTP']);
Route::post('/verify-otp', [RegisterController::class, 'verifyOTP']);
Route::post('/reset-password', [RegisterController::class, 'resetPassword']);
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/change-city', [RegisterController::class, 'changeCity']);

    Route::post('/personalize-skills', [RegisterController::class, 'personalizeSkills']);
});

