<?php

namespace Tests\Unit\Resources;

use App\Article;
use App\Http\Resources\ArticleResource;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function transform_article()
    {
        $article = factory(Article::class)->create();

        $response = ArticleResource::make($article)->resolve();

        $this->assertEquals($article->id, array_get($response, 'id'));
        $this->assertEquals($article->title, array_get($response, 'title'));
        $this->assertEquals($article->body, array_get($response, 'body'));
        $this->assertEquals($article->created_at->toAtomString(), array_get($response, 'created_at'));
        $this->assertEquals($article->updated_at->toAtomString(), array_get($response, 'updated_at'));
    }

    /** @test */
    public function transform_article_relationships()
    {
        $article = factory(Article::class)->create();

        $response = ArticleResource::make($article->load('author'))->resolve();

        $author = optional(array_get($response, 'author'))->resolve();
        $this->assertEquals($article->author->id, array_get($author, 'id'));
        $this->assertEquals($article->author->name, array_get($author, 'name'));
    }
}
