<?php

namespace App\Dto\Post;

use Illuminate\Http\UploadedFile;

readonly class UpdatePostDto
{
    public ?string $title;
    public ?string $description;
    public ?UploadedFile $image;
    public ?array $tags;

    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'tags' => $this->tags,
        ]);
    }
}
