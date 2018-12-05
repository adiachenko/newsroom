<?php

namespace Tests\Unit\Resources;

use App\Http\Resources\AccountResource;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccountResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function transform_account()
    {
        $user = factory(User::class)->create();

        $response = AccountResource::make($user)->resolve();

        $this->assertEquals($user->id, array_get($response, 'id'));
        $this->assertEquals($user->name, array_get($response, 'name'));
        $this->assertEquals($user->email, array_get($response, 'email'));
        $this->assertEquals($user->role, array_get($response, 'role'));
        $this->assertEquals($user->created_at->toAtomString(), array_get($response, 'created_at'));
        $this->assertEquals($user->updated_at->toAtomString(), array_get($response, 'updated_at'));
    }
}
