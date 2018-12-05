<?php

namespace Tests\Unit\Resources;

use App\Http\Resources\AuthorResource;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function transform_author()
    {
        $author = factory(User::class)->create();

        $response = AuthorResource::make($author)->resolve();

        $this->assertEquals($author->id, array_get($response, 'id'));
        $this->assertEquals($author->name, array_get($response, 'name'));
    }
}
