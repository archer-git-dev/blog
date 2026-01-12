<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DeletePostTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_post(): void
    {
        $user = $this->signIn();

        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('posts', ['id' => $post->id]);

        $response = $this->delete(route('post.destroy', $post->id));

        $response->assertRedirect(route('post.index'));
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}
