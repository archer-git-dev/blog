<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PostController extends Controller
{
    // Список всех постов
    public function index(): View
    {
        $posts = Post::with(['user', 'comments.user'])->orderBy('created_at', 'desc')->get();
        return view('post.index', compact('posts'));
    }

    // Форма создания поста
    public function create(): View
    {
        $tags = Tag::all();
        return view('post.create', compact('tags'));
    }

    // Сохранение нового поста
    public function store(CreatePostRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Обработка изображения
        if ($request->hasFile('image')) {
            $data['img_src'] = $request->file('image')->store('posts', 'public');
        }

        try {
            $post = Post::query()
                ->create([
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'img_src' => $data['img_src'],
                    'user_id' => auth()->id(),
                ]);


            // Прикрепляем теги
            if ($request->has('tags')) {
                $post->tags()->attach($data['tags']);
            }
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }


        return redirect()->route('post.index')->with('success', 'Post created successfully!');
    }

    // Просмотр одного поста
    public function show(int $postId): View
    {
        $post = Post::with('user')->findOrFail($postId);
        return view('post.show', compact('post'));
    }

    // Форма редактирования поста
    public function edit(int $postId): View
    {
        $post = Post::query()
            ->findOrFail($postId);

        // Проверяем, что пользователь может редактировать только свои посты
        Gate::authorize('update', $post);

        $tags = Tag::all();

        return view('post.edit', compact('post', 'tags'));
    }

    // Обновление поста
    public function update(UpdatePostRequest $request, int $postId): RedirectResponse
    {
        $post = Post::query()
            ->findOrFail($postId);

        // Проверяем права
        Gate::authorize('update', $post);

        try {
            $data = $request->validated();
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }

        // Обработка нового изображения
        if ($request->hasFile('image')) {
            // Удаляем старое изображение
            if ($post->img_src) {
                Storage::disk('public')->delete($post->img_src);
            }
            $data['img_src'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($data);

        // Синхронизируем теги
        $post->tags()->sync($request->tags ?? []);

        return redirect()->route('post.index')->with('success', 'Post updated successfully!');
    }

    // Удаление поста
    public function destroy(int $postId): RedirectResponse
    {
        $post = Post::query()
            ->findOrFail($postId);

        // Проверяем права
        Gate::authorize('delete', $post);

        // Удаляем изображение если есть
        if ($post->img_src) {
            Storage::disk('public')->delete($post->img_src);
        }

        try {
            $post->delete();
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('post.index')->with('success', 'Post deleted successfully!');
    }
}
