<?php

namespace App\Contracts\Post;

use App\Dto\Post\CreatePostDto;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

interface PostRepositoryContract
{
    public function getAllPosts(): Collection;

    public function storePost(CreatePostDto $createPostDto): Post;

    public function getPostById(int $postId): Post;

    public function update(array $data): void;

    public function destroy(int $postId): void;

    public function search(string $searchQuery): Collection;
}
