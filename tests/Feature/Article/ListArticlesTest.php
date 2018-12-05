<?php

namespace Tests\Feature\Article;

use App\Article;
use Carbon\Carbon;
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
                    'author' => ['id', 'name']
                ]
            ]
        ]);
    }

    /** @test */
    public function search_article_by_title()
    {
        factory(Article::class)->create(['title' => 'Alabama']);
        factory(Article::class)->create(['title' => 'Kentucky']);

        $response = $this->getJson('api/articles?search=Ala');

        $response->assertStatus(200);

        $response->assertJsonFragment(['title' => 'Alabama']);
        $response->assertJsonMissing(['title' => 'Kentucky']);
    }

    /** @test */
    public function filter_article_based_on_creation_date()
    {
        factory(Article::class)->create([
            'title' => 'Alabama',
            'created_at' => Carbon::create(2018, 10, 1),
        ]);
        factory(Article::class)->create([
            'title' => 'Kentucky',
            'created_at' => Carbon::create(2018, 11, 2),
        ]);

        $response = $this->getJson('api/articles?start_date=2018-10-01&end_date=2018-11-01');

        $response->assertJsonFragment(['title' => 'Alabama']);
        $response->assertJsonMissing(['title' => 'Kentucky']);
    }
}
