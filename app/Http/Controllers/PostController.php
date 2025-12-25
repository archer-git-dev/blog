<?php

namespace App\Http\Controllers;

use App\Contracts\Post\PostServiceContract;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(
        private readonly PostServiceContract $postService,
    )
    {
    }

    // Список всех постов
    public function index(): View
    {
        $posts = $this->postService->index();

        return view('post.index', compact('posts'));
    }

    // Форма создания поста
    public function create(): View
    {
        $tags = $this->postService->create();

        return view('post.create', compact('tags'));
    }

    // Сохранение нового поста
    public function store(CreatePostRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $this->postService->store($data);

        return redirect()->route('post.index')->with('success', 'Post created successfully!');
    }

    // Просмотр одного поста
    public function show(int $postId): View
    {
        $post = $this->postService->show($postId);

        return view('post.show', compact('post'));
    }

    // Форма редактирования поста
    public function edit(int $postId): View
    {
        $data = $this->postService->edit($postId);

        return view('post.edit', ['post' => $data['post'], 'tags' => $data['tags']]);
    }

    // Обновление поста
    public function update(UpdatePostRequest $request, int $postId): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['post_id'] = $postId;

            $this->postService->update($data);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('post.index')->with('success', 'Post updated successfully!');
    }

    // Удаление поста
    public function destroy(int $postId): RedirectResponse
    {
        try {
            $this->postService->destroy($postId);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('post.index')->with('success', 'Post deleted successfully!');
    }

    public function search(Request $request): View
    {
        $posts = $this->postService->search($request->query('query'));

        return view('post.index', compact('posts'));
    }
}
