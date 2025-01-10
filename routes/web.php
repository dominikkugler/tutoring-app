<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Ensure these routes are accessible only to authenticated users

// Public Routes
Route::get('/posts', [PostController::class, 'index'])->name('posts.index'); // Show all posts
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');


// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create'); // Show create post form
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store'); // Store new post
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit'); // Show edit form
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update'); // Update post
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy'); // Delete post
});

Route::middleware('auth')->group(function () {
    Route::get('/student-dashboard', [DashboardController::class, 'studentDashboard'])->name('student.dashboard');
    Route::get('/tutor-dashboard', [DashboardController::class, 'tutorDashboard'])->name('tutor.dashboard');
});

// Routes for messages
// Define the route for storing messages
Route::middleware(['auth'])->group(function () {
    Route::get('messages/create/{postId}', [MessageController::class, 'create'])->name('messages.create');
    Route::post('messages/store', [MessageController::class, 'store'])->name('messages.store'); // Store message route
});

// Route for viewing a single message (chat)
Route::get('/messages/{message}', [MessageController::class, 'show'])->name('messages.show');

// Route for viewing all messages
Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');



