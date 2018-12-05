<?php

namespace Tests\Feature\Article;

use App\Article;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function update_article()
    {
        $article = factory(Article::class)->create();

        $response = $this->patchJson('api/articles/1', [
            'title' => 'All that glitters is not gold',
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'
        ], [
            'Authorization' => "Bearer {$article->author->api_token}"
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id', 'title', 'body',
            ]
        ]);

        $this->assertDatabaseHas('articles', [
            'id' => 1,
            'title' => 'All that glitters is not gold',
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ]);
    }

    /** @test */
    public function check_user_is_granted_writer_privileges()
    {
        $article = factory(Article::class)->create([
            'author_id' => factory(User::class)->state(User::READER)
        ]);

        $response = $this->patchJson('api/articles/1', [
            'title' => 'All that glitters is not gold',
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'
        ], [
            'Authorization' => "Bearer {$article->author->api_token}"
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function check_user_owns_the_article()
    {
        factory(Article::class)->create();

        $anotherAuthor = factory(User::class)->state(User::WRITER)->create();

        $response = $this->patchJson('api/articles/1', [
            'title' => 'All that glitters is not gold',
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'
        ], [
            'Authorization' => "Bearer {$anotherAuthor->api_token}"
        ]);

        $response->assertStatus(403);
    }
}
