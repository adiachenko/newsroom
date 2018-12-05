<?php

namespace Tests\Feature\Article;

use App\Article;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_article()
    {
        factory(Article::class)->create();

        $response = $this->getJson('api/articles/1');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id', 'user_id', 'title', 'body',
            ]
        ]);
    }
}
