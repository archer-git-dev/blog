<?php

namespace App\Repositories\Tag;

use App\Contracts\Tag\TagRepositoryContract;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;


readonly class TagRepository implements TagRepositoryContract
{
    public function __construct(
        private Tag $tag
    )
    {
    }

    public function getAllTags(): Collection
    {
        return $this->tag->all();
    }
}
