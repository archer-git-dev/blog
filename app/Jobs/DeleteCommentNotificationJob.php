<?php

namespace App\Jobs;

use App\Models\Comment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeleteCommentNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public array $comment)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Уважаемый пользователь {$this->comment['user_id']}! Ваш комментарий {$this->comment['text']} был удален из-за несоответствия цензуры.");
    }
}
