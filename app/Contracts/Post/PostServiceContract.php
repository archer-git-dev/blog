<?php

namespace App\Contracts\Post;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

interface PostServiceContract
{
    public function index(): Collection;

    public function create(): Collection;

    public function store(array $data): void;

    public function show(int $postId): Post;

    public function edit(int $postId): array;

    public function update(array $data): void;

    public function destroy(int $postId): void;

    public function search(string $searchQuery): Collection;
}
