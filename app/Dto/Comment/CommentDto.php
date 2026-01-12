<?php

namespace App\Dto\Comment;

use App\Models\Comment;

class CommentDto
{
    public function __construct(
        public int $id,
        public string $text,
        public string $authorName,
        public string $createdAt,
    ) {}

    public static function fromModel(Comment $comment): self
    {
        return new self(
            id: $comment->id,
            text: $comment->text,
            authorName: $comment->user->name ?? 'Anon',
            createdAt: $comment->created_at->format('d.m.Y H:i'),
        );
    }
}
