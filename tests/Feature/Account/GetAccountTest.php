<?php

namespace Tests\Feature\Account;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetAccountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_account()
    {
        $user = factory(User::class)->create();

        $response = $this->getJson('api/account', [
            'Authorization' => "Bearer {$user->api_token}"
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id', 'name', 'email', 'role', 'created_at', 'updated_at',
            ]
        ]);
    }
}
