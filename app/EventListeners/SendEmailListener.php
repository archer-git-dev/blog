<?php

namespace App\EventListeners;

use App\Events\PostCreated;
use App\Jobs\SendEmailJob;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailListener implements ShouldQueue
{
    public function handle(PostCreated $event): void
    {
        SendEmailJob::dispatch($event->post);
    }
}
