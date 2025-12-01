<?php

namespace App\Providers;

use App\EventListeners\SendEmailListener;
use App\Events\CommentCreated;
use App\EventListeners\NewCommentEmailNotification;
use App\Events\PostCreated;
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
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

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
        Event::listen(PostCreated::class, SendEmailListener::class);

        // Обсервер для круд
        Comment::observe(CommentObserver::class);

        // Кастомизация e-mail подтверждения
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Подтверждение e-mail')
                ->line('Нажмите для подтверждения')
                ->action('Подтвердить', $url);
        });

    }
}
