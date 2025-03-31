<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Post\LocationController;
use App\Http\Controllers\Post\FollowController;
use App\Http\Controllers\Library\ResourceLibraryController;
use App\Http\Controllers\Post\PostInteractionController;

use App\Http\Controllers\Post\CommentActionController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Job\JobPostInteractionController;


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

    Route::post('/create-chat', [ChatController::class, 'createChat']);
    Route::post('/send-message', [ChatController::class, 'sendMessage']);
    Route::get('/chat-messages/{chat_id}', [ChatController::class, 'getChatMessages']);
    Route::get('/chats', [ChatController::class, 'getUserChats']);

    Route::post('/block-user', [RegisterController::class, 'blockUser']);
    Route::post('/unblock-user', [RegisterController::class, 'unblockUser']);
    Route::get('/featch-blocked-user', [RegisterController::class, 'getBlockedUsers']);

    Route::get('/teachers', [RegisterController::class, 'fetchTeachers']);
    Route::get('/teachers/{id}', [RegisterController::class, 'getTeacherById']);
    Route::get('/search-users', [RegisterController::class, 'searchUsers']);
    // Route::post('/approve-user/{id}', [UserController::class, 'approveUser']);
    // Route::post('/change-status/{id}', [UserController::class, 'changeStatus']);
    Route::post('/create-post', [PostController::class, 'store']);
    Route::get('/posts', [PostController::class, 'getPosts']);
    Route::get('/locations', [LocationController::class, 'ApigetLocations']);

    Route::post('/follow/{id}', [FollowController::class, 'followUser']);
    Route::post('/unfollow/{id}', [FollowController::class, 'unfollowUser']);
    Route::post('/follow-back/{id}', [FollowController::class, 'followBack']);
    Route::get('/followers', [FollowController::class, 'getFollowers']);
    Route::get('/following', [FollowController::class, 'getFollowing']);

    Route::get('/unique-subjects', [ResourceLibraryController::class, 'getUniqueSubjects']);
    Route::post('/post/{post}/interact', [PostInteractionController::class, 'store']);
    Route::get('/post/{post}/interactions', [PostInteractionController::class, 'index']);
    Route::post('/post/{post}/share' , [PostInteractionController::class, 'share']);
    Route::get('/post/{post}/share-count' , [PostInteractionController::class, 'getShareCount']);

    Route::post('/post/{post}/comments', [PostInteractionController::class, 'addComment']);
    Route::get('/post/{post}/comments', [PostInteractionController::class, 'fetchComments']);
    Route::put('/post/{post}/comments/{comment}', [PostInteractionController::class, 'updateComment']);
    Route::delete('/post/{post}/comments/{comment}', [PostInteractionController::class, 'deleteComment']);

    Route::post('/comments/{commentId}/like', [CommentActionController::class, 'like']);
    Route::post('/comments/{commentId}/reply', [CommentActionController::class, 'reply']);
    Route::get('/comments/{commentId}/replies', [CommentActionController::class, 'fetchReplies']);

    Route::get('/recently-shared', [PostInteractionController::class, 'recentlyShared']);

    Route::get('/subjects', [SubjectController::class, 'fetchSubjects'])->name('api.fetch.subjects');

    // API: Get All Events
    Route::get('/events', [EventController::class, 'getEvents']);

    // API: Store Event
    Route::post('/events', [EventController::class, 'storeEventApi']);

    // API: Delete Event
    Route::delete('/events/{id}', [EventController::class, 'deleteEventApi']);

    Route::get('/events/{id}', [EventController::class, 'getEventById']);

    Route::post('/job-posts/{jobId}/interact', [JobPostInteractionController::class, 'store']);
    Route::get('/job-posts/{jobId}/comments', [JobPostInteractionController::class, 'fetchComments']);
    Route::delete('/job-posts/{jobId}/comments/{commentId}', [JobPostInteractionController::class, 'deleteComment']);
    Route::put('/job-posts/{jobId}/comments/{commentId}', [JobPostInteractionController::class, 'updateComment']);
    Route::get('/job-post/{postId}/like-count', [JobPostInteractionController::class, 'getLikeCount']);

});



