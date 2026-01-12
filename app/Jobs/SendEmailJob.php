<?php

namespace App\Jobs;

use App\Mail\CreatedPostMail;
use App\Mail\WelcomeMail;
use App\Models\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Post $post)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $title = $this->post->title;
        $body = $this->post->description;

        Mail::to('karagez28@gmail.com')->send(new CreatedPostMail($title, $body));
    }
}
