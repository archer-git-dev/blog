<?php

namespace App\Console\Commands;

use App\Models\Comment;
use Illuminate\Console\Command;

class DeleteComments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-comments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all comments where text < 3 symbols';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Comment::query()
            ->whereRaw('LENGTH(text) < 3')
            ->delete();
    }
}
