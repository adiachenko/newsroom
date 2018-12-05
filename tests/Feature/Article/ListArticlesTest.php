<?php

namespace Tests\Feature\Article;

use App\Article;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function list_articles()
    {
        factory(Article::class)->create();

        $response = $this->getJson('api/articles');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'title',
                    'body',
                ]
            ]
        ]);
    }
}
