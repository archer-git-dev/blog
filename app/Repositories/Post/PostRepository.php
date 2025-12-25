<?php

namespace App\Repositories\Post;

use App\Contracts\Post\PostRepositoryContract;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
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
        return $this->post->with(['user', 'comments.user'])->orderBy('created_at', 'desc')->get();
    }

    public function storePost(array $data): Post
    {
        // Используем транзакцию: если теги не привяжутся, пост тоже не создастся
        return DB::transaction(function () use ($data) {

            // 1. Создаем сам пост
            $post = $this->post->query()->create([
                'title' => $data['title'],
                'description' => $data['description'],
                'img_src' => $data['img_src'],
                'user_id' => $data['user_id'],
            ]);

            // 2. Привязываем теги (если они есть)
            if (!empty($data['tags'])) {
                $post->tags()->attach($data['tags']);
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
