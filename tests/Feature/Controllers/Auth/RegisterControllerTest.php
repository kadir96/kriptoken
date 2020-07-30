<?php

namespace Tests\Feature\Controllers\Auth;

use App\Listeners\GiftRippleToNewUser;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Ripple currency as registered user earns some ripple.
        factory(Currency::class)->create([
            'symbol' => 'XRP',
        ]);
    }

    /**
     * @test
     */
    public function test_register_successfully()
    {
        $this->assertEquals(0, User::count());

        $response = $this->postJson(route('register'), [
            'name' => 'Jane',
            'surname' => 'Doe',
            'email' => 'jane@doe.com',
            'password' => 1234,
        ]);

        $response->assertStatus(201);
        $this->assertEquals(1, User::count());

        $user = User::first();

        $response->assertJsonFragment([
            'id' => $user->id,
            'name' => 'Jane',
            'surname' => 'Doe',
            'email' => 'jane@doe.com',
        ]);

        $this->assertNotNull($response->json('data.token'));
        $this->assertEquals($user->id, auth()->setToken($response->json('data.token'))->user()->id);
    }

    /**
     * @test
     */
    public function test_registered_user_earns_ripple()
    {
        $response = $this->postJson(route('register'), [
            'name' => 'Jane',
            'surname' => 'Doe',
            'email' => 'jane@doe.com',
            'password' => 1234,
        ]);

        $this->assertEquals(
            GiftRippleToNewUser::AMOUNT_TO_GIFT,
            User::first()->accountForCurrency(Currency::bySymbol('XRP'))->balance
        );

    }

    /**
     * @test
     */
    public function test_name_is_required()
    {
        $response = $this->postJson(route('register'), [
            'surname' => 'Doe',
            'email' => 'jane@doe.com',
            'password' => 1234,
        ]);

        $response->assertStatus(422);
        $this->assertEquals(0, User::count());
        $response->assertJsonValidationErrors('name');
    }

    /**
     * @test
     */
    public function test_surname_is_required()
    {
        $response = $this->postJson(route('register'), [
            'name' => 'Jane',
            'email' => 'jane@doe.com',
            'password' => 1234,
        ]);

        $response->assertStatus(422);
        $this->assertEquals(0, User::count());
        $response->assertJsonValidationErrors('surname');
    }

    /**
     * @test
     */
    public function test_email_is_required()
    {
        $response = $this->postJson(route('register'), [
            'name' => 'Jane',
            'surname' => 'Doe',
            'password' => 1234,
        ]);

        $response->assertStatus(422);
        $this->assertEquals(0, User::count());
        $response->assertJsonValidationErrors('email');
    }

    /**
     * @test
     */
    public function test_email_should_be_unique()
    {
        $user = factory(User::class)->create();

        $response = $this->postJson(route('register'), [
            'name' => 'Jane',
            'surname' => 'Doe',
            'email' => $user->email,
            'password' => 1234,
        ]);

        $response->assertStatus(422);
        $this->assertEquals(1, User::count());
        $response->assertJsonValidationErrors('email');
    }

    /**
     * @test
     */
    public function test_email_should_be_valid()
    {
        $response = $this->postJson(route('register'), [
            'name' => 'Jane',
            'surname' => 'Doe',
            'email' => 'a_not_valid@email',
            'password' => 1234,
        ]);

        $response->assertStatus(422);
        $this->assertEquals(0, User::count());
        $response->assertJsonValidationErrors('email');
    }

    /**
     * @test
     */
    public function test_password_is_required()
    {
        $response = $this->postJson(route('register'), [
            'name' => 'Jane',
            'surname' => 'Doe',
            'email' => 'jane@doe.com',
        ]);

        $response->assertStatus(422);
        $this->assertEquals(0, User::count());
        $response->assertJsonValidationErrors('password');
    }
}
