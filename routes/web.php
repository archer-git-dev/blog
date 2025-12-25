<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Invoke\InvokeController;
use App\Http\Controllers\PostController;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('post.index')
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


// Админка
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/blog-stats', [AdminController::class, 'stats'])->name('stats');
});

// Email верификация

// Страница текстом, что нужно подтвердить e-mail
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Процесс верификации (подтверждения) e-mail
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect()->route('post.index');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Повторная отправка письма с подтверждением
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


//===========================


// Поиск
Route::get('/search', [PostController::class, 'search'])->name('search');


// Контроллер одного действия - invoke
Route::middleware('auth')->group(function () {
    Route::get('/invoke', InvokeController::class)->name('invoke');
});

// Будет выполняться при любом несуществующем url
Route::fallback(function () {
    var_dump('Hello World!');
});
