<?php

namespace App\Jobs;

use App\Models\Comment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendCommentNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Comment $comment)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Находим автора поста
        $postAuthor = $this->comment->post->user;

        // Логируем вместо реальной отправки email
        Log::info("📧 Email отправлен автору: {$postAuthor->name}");
        Log::info("💬 Комментарий от: {$this->comment->user->name}");
        Log::info("📝 Текст: {$this->comment->text}");

    }
}
