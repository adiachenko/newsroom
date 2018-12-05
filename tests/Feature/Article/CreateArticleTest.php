<?php

namespace Tests\Feature\Article;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_article()
    {
        $user = factory(User::class)->state(User::WRITER)->create();

        $response = $this->postJson('api/articles', [
            'title' => 'All that glitters is not gold',
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'
        ], [
            'Authorization' => "Bearer {$user->api_token}"
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id', 'title', 'body',
            ]
        ]);

        $this->assertDatabaseHas('articles', [
            'author_id' => $user->id,
            'title' => 'All that glitters is not gold',
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ]);
    }

    /** @test */
    public function check_user_is_granted_writer_privileges()
    {
        $user = factory(User::class)->create();

        $response = $this->postJson('api/articles', [
            'title' => 'All that glitters is not gold',
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'
        ], [
            'Authorization' => "Bearer {$user->api_token}"
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function check_required_fields_are_present()
    {
        $user = factory(User::class)->state(User::WRITER)->create();

        $response = $this->postJson('api/articles', [
            // Oops! ;)
        ], [
            'Authorization' => "Bearer {$user->api_token}"
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'body']);
    }
}
