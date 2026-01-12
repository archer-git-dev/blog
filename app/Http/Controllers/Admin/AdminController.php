<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    public function stats(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $stats = Cache::remember('blog.stats', 600, function () {
            return [
                'total_posts' => Post::query()->count(),
                'total_comments' => Comment::query()->count(),
                'tot_post_title' => Post::query()->withCount('comments')->orderByDesc('comments_count')->value('title'),
            ];
        });

        return view('admin.stats', compact('stats'));
    }
}
