<?php

namespace App\Repositories\Post;

use App\Contracts\Post\PostRepositoryContract;
use App\Dto\Post\CreatePostDto;
use App\Dto\Post\PostIndexDto;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

readonly class PostRepository implements PostRepositoryContract
{
    public function __construct(
        private Post $post
    )
    {
    }

    public function getAllPosts(): Collection
    {
        return Cache::remember('posts:all', 60 * 60, function () {
            $posts = Post::query()
                // Обязательно грузим связи, иначе будет N+1
                ->with(['user', 'tags', 'comments.user'])
                ->latest()
                ->get();

            return $posts->map(fn(Post $post) => PostIndexDto::fromModel($post));
        });
    }

    public function storePost(CreatePostDto $createPostDto): Post
    {
        // Используем транзакцию: если теги не привяжутся, пост тоже не создастся
        return DB::transaction(function () use ($data) {

            // 1. Создаем сам пост
            $post = $this->post->query()->create([
                'title' => $createPostDto->title,
                'description' => $createPostDto->description,
                'img_src' => $createPostDto->imageSrc,
                'user_id' => $createPostDto->authorId,
            ]);

            // 2. Привязываем теги (если они есть)
            if (!empty($createPostDto->tags)) {
                $post->tags()->attach($createPostDto->tags);
            }

            return $post;
        });
    }

    public function getPostById(int $postId): Post
    {
        return $this->post->with('user')->findOrFail($postId);
    }

    public function update(array $data): void
    {
        $post = $this->post->query()->findOrFail($data['post_id']);

        // Используем транзакцию: если теги не привяжутся, пост тоже не создастся
        DB::transaction(function () use ($data, $post) {
            $post->update($data);

            // Синхронизируем теги
            $post->tags()->sync($data['tags'] ?? []);
        });
    }

    public function destroy(int $postId): void
    {
        $post = $this->post->query()->findOrFail($postId);

        $post->delete();
    }

    public function search(string $searchQuery): Collection
    {
        return $this->post->search($searchQuery)->get();
    }
}
