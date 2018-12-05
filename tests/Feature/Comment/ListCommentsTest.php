<?php

namespace Tests\Feature\Comment;

use App\Comment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListCommentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function list_comments()
    {
        factory(Comment::class)->create();

        $response = $this->getJson('api/comments');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'article_id',
                    'body',
                    'author' => ['id', 'name']
                ]
            ]
        ]);
    }

    /** @test */
    public function filter_comments_by_article()
    {
        factory(Comment::class)->times(2)->create();

        $response = $this->getJson('api/comments?article=2');

        $response->assertStatus(200);
        $response->assertJsonFragment(['article_id' => '2']);
        $response->assertJsonMissing(['article_id' => '1']);
    }
}
