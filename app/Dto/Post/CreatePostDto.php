<?php

namespace App\Dto\Post;

use Illuminate\Http\UploadedFile;

class CreatePostDto
{
    public function __construct(
        public string $title,
        public string $description,
        public ?UploadedFile $image,
        public ?string $imageSrc,
        public array $tags,
        public int $authorId,
    )
    {
    }

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
