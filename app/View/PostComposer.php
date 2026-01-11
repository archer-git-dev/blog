<?php

namespace App\View;

use App\Models\Post;
use Illuminate\View\View;

readonly class PostComposer
{
    public function __construct(
        private Post $post
    )
    {
    }

    public function compose(View $view): void
    {
        $view->with('postCount', $this->post->all()->count());
    }
}
