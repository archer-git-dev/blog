<?php

namespace App\Http\Controllers;

use App\Events\CommentCreated;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function store(CommentRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        try {
            $comment = Comment::query()
                ->create([
                    'text' => $data['text'],
                    'user_id' => $data['user_id'],
                    'post_id' => $data['post_id'],
                ]);

            event(new CommentCreated($comment));

            // Чистим кэш для статистики
            Cache::forget('blog.stats');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('post.show', $data['post_id'])
            ->with('success', 'Comment added successfully!');
    }

    public function edit(int $commentId): View
    {
        $comment = Comment::with(['post', 'user'])->findOrFail($commentId);

        Gate::authorize('update', $comment);

        return view('comment.edit', compact('comment'));
    }

    public function update(CommentRequest $request, int $commentId): RedirectResponse
    {
        $comment = Comment::query()
            ->findOrFail($commentId);

        Gate::authorize('update', $comment);

        try {
            $comment->update($request->validated());
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('post.show', $comment->post_id)
            ->with('success', 'Comment updated successfully!');
    }

    public function destroy(int $commentId): RedirectResponse
    {
        $comment = Comment::query()
            ->findOrFail($commentId);

        $postId = $comment->post_id;

        Gate::authorize('delete', $comment);

        try {
            $comment->delete();
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('post.show', $postId)
            ->with('success', 'Comment deleted successfully!');
    }
}
