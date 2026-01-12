<?php

namespace App\Dto\Post;

use App\Dto\Comment\CommentDto;
use App\Dto\Tag\TagDto;
use App\Models\Post;

readonly class PostIndexDto
{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public ?string $imgSrc,
        public string $authorName,
        public string $createdAt,

        /** @var TagDto[] */
        public array $tags,

        /** @var CommentDto[] */
        public array $comments,
    ) {}


    public static function fromModel(Post $post): self
    {
        return new self(
            id: $post->id,
            title: $post->title,
            description: self::sliceDescription($post->description),
            imgSrc: $post->img_src ? asset('storage/' . $post->img_src) : null,
            authorName: $post->user->name ?? 'Unknown',
            createdAt: $post->created_at->format('d.m.Y H:i'),
            tags: $post->tags->map(fn($tag) => TagDto::fromModel($tag))->toArray(),
            comments: $post->comments->map(fn($comment) => CommentDto::fromModel($comment))->toArray(),
        );
    }

    public static function sliceDescription($description, $limit = 100): string
    {
        return \Illuminate\Support\Str::limit($description, $limit);
    }
}
