<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Создаем теги если их нет
    $this->call(TagSeeder::class);
    $tags = Tag::all();

    // Создаем пользователей (добавляем к существующим)
    $users = User::factory(10)->create();

    // Создаем посты для каждого пользователя
    foreach ($users as $user) {
        $posts = Post::factory(10)->create([
            'user_id' => $user->id
        ]);

        foreach ($posts as $post) {
            // Добавляем случайные теги к посту
            $postTags = $tags->random(rand(1, 3))->pluck('id');
            $post->tags()->sync($postTags);

//            // Создаем комментарии
//            Comment::factory(5)->create([
//                'post_id' => $post->id,
//                'user_id' => $users->random()->id
//            ]);
        }
    }

    // Создаем тестового пользователя
    User::firstOrCreate(
        ['email' => 'test@example.com'],
        [
            'name' => 'Test User',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]
    );
    }
}
