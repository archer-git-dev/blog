<?php

namespace App\Observers;


use App\Models\Comment;
use App\Models\Test;

class CommentObserver
{
    /**
     * Handle the AppModelsComment "created" event.
     */
    public function created(Comment $model): void
    {

    }

    /**
     * Handle the AppModelsComment "updated" event.
     */
    public function updated(Comment $model): void
    {
        //
    }

    /**
     * Handle the AppModelsComment "deleted" event.
     */
    public function deleted(Comment $model): void
    {
        //
    }

    /**
     * Handle the AppModelsComment "restored" event.
     */
    public function restored(Comment $model): void
    {
        //
    }

    /**
     * Handle the AppModelsComment "force deleted" event.
     */
    public function forceDeleted(Comment $model): void
    {
        //
    }
}
