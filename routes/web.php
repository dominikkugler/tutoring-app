<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PostController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TutorTeachingController; // Import the TutorTeachingController

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Auth::routes();

// Home route
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Public Routes
Route::get('/posts', [PostController::class, 'index'])->name('posts.index'); // Show all posts

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Post management
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create'); // Show create form
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store'); // Store new post
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit'); // Show edit form
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update'); // Update post
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy'); // Delete post

    // Dashboard routes
    Route::get('/student-dashboard', function () {
        if (auth()->user()->isStudent()) {
            return app(DashboardController::class)->studentDashboard();
        }
        return redirect('/')->with('error', 'You are not authorized to access this page.');
    })->name('student.dashboard');

    Route::get('/tutor-dashboard', function () {
        if (auth()->user()->isTutor()) {
            return app(DashboardController::class)->tutorDashboard();
        }
        return redirect('/')->with('error', 'You are not authorized to access this page.');
    })->name('tutor.dashboard');

    // Chat routes (for students and tutors)
    Route::get('chat/{chatUserId}', [DashboardController::class, 'showChat'])->name('chat.show'); // View chat for students
    Route::post('chat/{chatUserId}/send', [DashboardController::class, 'sendMessage'])->name('chat.send'); // Send message from student

    Route::get('chat/tutor/{chatUserId}', [DashboardController::class, 'showChatTutor'])->name('chat.show.tutor'); // View chat for tutors
    Route::post('chat/tutor/{chatUserId}/send', [DashboardController::class, 'sendMessageTutor'])->name('chat.send.tutor'); // Send message from tutor

    // Message management
    Route::get('messages/create/{postId}', [MessageController::class, 'create'])->name('messages.create'); // Show create message form
    Route::post('messages/store', [MessageController::class, 'store'])->name('messages.store'); // Store message
    Route::get('/messages/{message}', [MessageController::class, 'show'])->name('messages.show'); // Show single message
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index'); // Show all messages

    // Tutor Teachings routes
    Route::get('/tutor-teachings', [TutorTeachingController::class, 'index'])->name('teachings.index'); // List all teachings
    Route::get('/tutor-teachings/create', [TutorTeachingController::class, 'create'])->name('teachings.create'); // Show create teaching form
    Route::post('/tutor-teachings', [TutorTeachingController::class, 'store'])->name('teachings.store'); // Add a new teaching
    Route::delete('/tutor-teachings/{id}', [TutorTeachingController::class, 'destroy'])->name('teachings.destroy'); // Delete a teaching
});

Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show'); // Show single post
