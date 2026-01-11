<?php

namespace App\Providers;

use App\Contracts\Post\PostRepositoryContract;
use App\Contracts\Post\PostServiceContract;
use App\Contracts\Tag\TagRepositoryContract;
use App\EventListeners\NewCommentEmailNotification;
use App\EventListeners\SendEmailListener;
use App\Events\CommentCreated;
use App\Events\PostCreated;
use App\Models\Comment;
use App\Models\Post;
use App\Observers\CommentObserver;
use App\Policies\CommentPolicy;
use App\Policies\PostPolicy;
use App\Repositories\Post\PostRepository;
use App\Repositories\Tag\TagRepository;
use App\Services\Post\PostService;
use App\View\PostComposer;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PostServiceContract::class, PostService::class);
        $this->app->bind(PostRepositoryContract::class, PostRepository::class);

        $this->app->bind(TagRepositoryContract::class, TagRepository::class);
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
