<?php

namespace Tests\Feature\Account;

use App\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateAccountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_account()
    {
        $response = $this->postJson('api/account', [
            'name' => 'Jack Bauer',
            'email' => 'jack.bauer@ctu.org',
            'password' => 'secret',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id', 'name', 'email', 'role', 'created_at', 'updated_at',
            ]
        ]);

        $this->assertDatabaseHas('users', [
            'role' => 'reader',
            'name' => 'Jack Bauer',
            'email' => 'jack.bauer@ctu.org',
        ]);

        // Make sure the password has been hashed correctly
        $this->assertTrue(Auth::attempt([
            'email' => 'jack.bauer@ctu.org',
            'password' => 'secret',
        ]));
    }

    /** @test */
    public function check_required_fields_are_present()
    {
        $response = $this->postJson('api/account', [
            // Oops! ;)
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    /** @test */
    public function check_email_is_valid_email()
    {
        $response = $this->postJson('api/account', [
            'email' => 'Muahaha!'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    /** @test */
    public function check_email_is_unique()
    {
        factory(User::class)->create(['email' => 'jack.bauer@ctu.org']);

        $response = $this->postJson('api/account', [
            'name' => 'Jack Bauer',
            'email' => 'jack.bauer@ctu.org',
            'password' => 'secret',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }
}
