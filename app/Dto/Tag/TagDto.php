<?php

namespace App\Dto\Tag;

use App\Models\Tag;

class TagDto
{
    public function __construct(
        public int $id,
        public string $title,
    ) {}

    public static function fromModel(Tag $tag): self
    {
        return new self(
            id: $tag->id,
            title: $tag->title,
        );
    }
}
