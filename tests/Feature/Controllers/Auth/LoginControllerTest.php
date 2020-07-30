<?php

namespace Tests\Feature\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    public function test_login_successfully()
    {
        $response = $this->postJson(route('login'), [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $this->assertNotNull($response->json('token'));
        $this->assertEquals($this->user->id, auth()->setToken($response->json('token'))->user()->id);
    }

    public function test_email_is_required()
    {
        $response = $this->postJson(route('login'), [
            'password' => 'password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    public function test_email_should_be_valid()
    {
        $response = $this->postJson(route('login'), [
            'email' => 'not_valid@email',
            'password' => 'password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    public function test_password_is_required()
    {
        $response = $this->postJson(route('login'), [
            'email' => $this->user->email,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('password');
    }

    public function test_login_fails_when_non_existing_email_provided()
    {
        $response = $this->postJson(route('login'), [
            'email' => 'nonexisting@email.com',
            'password' => 'password',
        ]);

        $response->assertStatus(401);
    }

    public function test_login_fails_when_given_credentials_does_not_match_with_user_credentials()
    {
        $response = $this->postJson(route('login'), [
            'email' => $this->user->email,
            'password' => 'non-matching-password',
        ]);

        $response->assertStatus(401);
    }
}
