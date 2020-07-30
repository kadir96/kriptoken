<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_authenticated_user_info_successfully()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->getJson(route('user'));

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $user->id,
            'name' => $user->name,
            'surname' => $user->surname,
            'email' => $user->email,
        ]);
    }

    public function test_unauthenticated_user_can_not_access()
    {
        $this->getJson(route('user'))->assertStatus(401);
    }
}
