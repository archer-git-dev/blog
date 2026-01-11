<?php

namespace App\Services\Post;

use App\Contracts\Post\PostRepositoryContract;
use App\Contracts\Post\PostServiceContract;
use App\Contracts\Tag\TagRepositoryContract;
use App\Events\PostCreated;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

readonly class PostService implements PostServiceContract
{
    public function __construct(
        private PostRepositoryContract $postRepository,
        private TagRepositoryContract $tagRepository
    )
    {
    }

    public function index(): Collection
    {
        return $this->postRepository->getAllPosts();
    }

    public function create(): Collection
    {
        return $this->tagRepository->getAllTags();
    }

    public function store(array $data): void
    {
        // Обработка изображения
        if (isset($data['image'])) {
            $data['img_src'] = $data['image']->store('posts', 'public');
        }

        $data['user_id'] = auth()->id();

        $post = $this->postRepository->storePost($data);

        // Вызываем слушателей при событии создания
        event(new PostCreated($post));

        // Чистим кеш для всех постов
        Cache::forget('posts:all');

        // Чистим кэш для статистики
        Cache::forget('blog.stats');
    }

    public function show(int $postId): Post
    {
        return $this->postRepository->getPostById($postId);
    }

    public function edit(int $postId): array
    {
        $post = $this->postRepository->getPostById($postId);

        // Проверяем, что пользователь может редактировать только свои посты
        Gate::authorize('update', $post);

        $tags = $this->tagRepository->getAllTags();

        return ['post' => $post, 'tags' => $tags];
    }

    public function update(array $data): void
    {
        $post = $this->postRepository->getPostById($data['post_id']);

        // Проверяем права
        Gate::authorize('update', $post);

        // Обработка нового изображения
        if (isset($data['image'])) {
            // Удаляем старое изображение
            if ($post->img_src) {
                Storage::disk('public')->delete($post->img_src);
            }
            $data['img_src'] = $data['image']->store('posts', 'public');
        }

        $this->postRepository->update($data);

        // Чистим кеш для всех постов
        Cache::forget('posts:all');
    }

    public function destroy(int $postId): void
    {
        $post = $this->postRepository->getPostById($postId);

        // Проверяем права
        Gate::authorize('delete', $post);

        // Удаляем изображение если есть
        if ($post->img_src) {
            Storage::disk('public')->delete($post->img_src);
        }

        $this->postRepository->destroy($postId);

        // Чистим кеш для всех постов
        Cache::forget('posts:all');
    }

    public function search(string $searchQuery): Collection
    {
        return $this->postRepository->search($searchQuery);
    }
}
