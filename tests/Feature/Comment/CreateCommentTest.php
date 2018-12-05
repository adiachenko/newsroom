<?php

namespace Tests\Feature\Comment;

use App\Article;
use App\Notifications\CommentCreated;
use App\User;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_comment()
    {
        $article = factory(Article::class)->create();
        $user = factory(User::class)->state(User::READER)->create();

        $response = $this->postJson('api/comments', [
            'article_id' => $article->id,
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ], [
            'Authorization' => "Bearer {$user->api_token}"
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id', 'article_id', 'body',
            ]
        ]);

        $this->assertDatabaseHas('comments', [
            'article_id' => $article->id,
            'author_id' => $user->id,
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ]);
    }

    /** @test */
    public function send_mail_notification_to_article_author()
    {
        Notification::fake();

        $article = factory(Article::class)->create();
        $user = factory(User::class)->state(User::READER)->create();

        $this->postJson('api/comments', [
            'article_id' => $article->id,
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ], [
            'Authorization' => "Bearer {$user->api_token}"
        ]);

        Notification::assertSentTo($article->author, CommentCreated::class);
    }

    /** @test */
    public function check_user_is_granted_reader_privileges()
    {
        $article = factory(Article::class)->create();
        $user = factory(User::class)->state(User::WRITER)->create();

        $response = $this->postJson('api/comments', [
            'article_id' => $article->id,
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ], [
            'Authorization' => "Bearer {$user->api_token}"
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function check_required_fields_are_present()
    {
        $user = factory(User::class)->state(User::READER)->create();

        $response = $this->postJson('api/comments', [
            // Oops! ;)
        ], [
            'Authorization' => "Bearer {$user->api_token}"
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('body');
    }
}
