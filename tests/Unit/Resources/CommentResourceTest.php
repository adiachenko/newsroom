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
    public function transform_article()
    {
        $comment = factory(Comment::class)->create();

        $response = CommentResource::make($comment)->resolve();

        $this->assertEquals($comment->id, array_get($response, 'id'));
        $this->assertEquals($comment->article_id, array_get($response, 'article_id'));
        $this->assertEquals($comment->body, array_get($response, 'body'));
        $this->assertEquals($comment->created_at->toAtomString(), array_get($response, 'created_at'));
        $this->assertEquals($comment->updated_at->toAtomString(), array_get($response, 'updated_at'));
    }
}
