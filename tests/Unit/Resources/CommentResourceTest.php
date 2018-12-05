<?php

namespace Tests\Unit\Resources;

use App\Comment;
use App\Http\Resources\CommentResource;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function transform_comment()
    {
        $comment = factory(Comment::class)->create();

        $response = CommentResource::make($comment)->resolve();

        $this->assertEquals($comment->id, array_get($response, 'id'));
        $this->assertEquals($comment->article_id, array_get($response, 'article_id'));
        $this->assertEquals($comment->body, array_get($response, 'body'));
        $this->assertEquals($comment->created_at->toAtomString(), array_get($response, 'created_at'));
        $this->assertEquals($comment->updated_at->toAtomString(), array_get($response, 'updated_at'));
    }

    /** @test */
    public function transform_comment_relationships()
    {
        $comment = factory(Comment::class)->create();

        $response = CommentResource::make($comment->load('author'))->resolve();

        $author = optional(array_get($response, 'author'))->resolve();
        $this->assertEquals($comment->author->id, array_get($author, 'id'));
        $this->assertEquals($comment->author->name, array_get($author, 'name'));
    }
}
