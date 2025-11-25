<?php

namespace App\Providers;

use App\Events\CommentCreated;
use App\EventListeners\NewCommentEmailNotification;
use App\Models\Comment;
use App\Models\Post;
use App\Observers\CommentObserver;
use App\Policies\CommentPolicy;
use App\Policies\PostPolicy;
use App\View\PostComposer;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Testing\Fakes\EventFake;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Post::class, PostPolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);

        // Использование компоновщика на основе класса
        View::composer('post.*', PostComposer::class);

        // Событие и слушатели
        Event::listen(CommentCreated::class, NewCommentEmailNotification::class);

        // Обсервер для круд
        Comment::observe(CommentObserver::class);
    }
}
