<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Chat\ChatController;

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
    Route::get('/profile', [RegisterController::class, 'getProfile']);
    // 🔹 Chat System Routes
    Route::post('/create-chat', [ChatController::class, 'createChat']);
    Route::post('/send-message', [ChatController::class, 'sendMessage']);
    Route::get('/chat-messages/{chat_id}', [ChatController::class, 'getChatMessages']);

    Route::post('/block-user', [RegisterController::class, 'blockUser']);
    Route::post('/unblock-user', [RegisterController::class, 'unblockUser']);
    Route::get('/featch-blocked-user', [RegisterController::class, 'getblockedProfile']);
});


