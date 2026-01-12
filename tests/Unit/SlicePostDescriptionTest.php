<?php

namespace Tests\Unit;

use App\Dto\Comment\CommentDto;
use App\Dto\Post\PostIndexDto;
use App\Dto\Tag\TagDto;
use PHPUnit\Framework\TestCase;

class SlicePostDescriptionTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_slice_post_description(): void
    {
        $post = new PostIndexDto(
            id: 1,
            title: 'title',
            description: 'Just moles dictum. Pellentesque habitasse accumsan ex. Amet, habitasse cursus risus pulvinar in ',
            imgSrc: 'test.png',
            authorName: 'author',
            createdAt: '10.10.2025',
            tags: [new TagDto(id: 1, title: 'title_tag')],
            comments: [new CommentDto(id: 1, text: 'body', authorName: 'author', createdAt: '10.10.2025')],
        );

        $response = $post::sliceDescription($post->description, 10);

        $this->assertEquals('Just moles...', $response);
    }
}
