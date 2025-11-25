<?php

namespace App\EventListeners;

use App\Events\CommentCreated;
use App\Jobs\SendCommentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class NewCommentEmailNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(CommentCreated $event): void
    {
        Log::info("=== EVENT ID: {$event->eventId}} ===");
        Log::info("✅ Задача поставлена в очередь для комментария ID: {$event->comment->id}");

        SendCommentNotification::dispatch($event->comment);
    }
}
