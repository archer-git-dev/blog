<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('posts.index')
        : redirect()->route('login');
})->name('home');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'viewRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'viewLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Posts
Route::middleware('auth')->name('post.')->prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('index');
    Route::get('/create', [PostController::class, 'create'])->name('create');
    Route::post('/', [PostController::class, 'store'])->name('store');
    Route::get('/{post_id}', [PostController::class, 'show'])->name('show');
    Route::get('/{post_id}/edit', [PostController::class, 'edit'])->name('edit');
    Route::put('/{post_id}', [PostController::class, 'update'])->name('update');
    Route::delete('/{post_id}', [PostController::class, 'destroy'])->name('destroy');
});

// Comments routes
Route::middleware('auth')->prefix('comment')->name('comment.')->group(function () {
    Route::post('/', [CommentController::class, 'store'])->name('store');
    Route::get('/{comment_id}/edit', [CommentController::class, 'edit'])->name('edit');
    Route::put('/{comment_id}', [CommentController::class, 'update'])->name('update');
    Route::delete('/{comment_id}', [CommentController::class, 'destroy'])->name('destroy');
});


// Будет выполняться при любом несуществующем url
Route::fallback(function () {
    var_dump('Hello World!');
});
