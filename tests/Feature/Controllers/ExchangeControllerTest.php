<?php

namespace Tests\Feature\Controllers;

use App\Models\Account;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExchangeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Account
     */
    private $fromAccount;

    /**
     * @var Account
     */
    private $toAccount;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        $currency1 = factory(Currency::class)->create([
            'btc_value' => 2,
        ]);
        $currency2 = factory(Currency::class)->create([
            'btc_value' => 1,
        ]);
        $this->fromAccount = tap($this->user->accountForCurrency($currency1), function (Account $account) {
            $account
                ->addBalance(10)
                ->save();
        });
        $this->toAccount = $this->user->accountForCurrency($currency2);
    }

    public function test_exchange_successfully()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->user);

        $response = $this->postJson(route('exchange'), [
            'from' => $this->fromAccount->currency->symbol,
            'to' => $this->toAccount->currency->symbol,
            'amount' => 1,
        ]);

        $response->assertStatus(200);

        $this->fromAccount->refresh();
        $this->toAccount->refresh();

        $this->assertEquals($this->fromAccount->balance, $response->json('data.accounts.from.balance'));
        $this->assertEquals($this->toAccount->balance, $response->json('data.accounts.to.balance'));

        $response->assertJsonFragment([
            'accounts' => [
                'from' => [
                    'id' => $this->fromAccount->id,
                    'balance' => $this->fromAccount->formatted_balance,
                    'currency' => [
                        'id' => $this->fromAccount->currency->id,
                        'name' => $this->fromAccount->currency->name,
                        'symbol' => $this->fromAccount->currency->symbol,
                        'btc_value' => $this->fromAccount->currency->formatted_btc_value,
                    ],
                ],
                'to' => [
                    'id' => $this->toAccount->id,
                    'balance' => $this->toAccount->formatted_balance,
                    'currency' => [
                        'id' => $this->toAccount->currency->id,
                        'name' => $this->toAccount->currency->name,
                        'symbol' => $this->toAccount->currency->symbol,
                        'btc_value' => $this->toAccount->currency->formatted_btc_value,
                    ],
                ]
            ]
        ]);

    }

    public function test_from_currency_symbol_required()
    {
        $this->actingAs($this->user);

        $response = $this->postJson(route('exchange'), [
            'to' => $this->toAccount->currency->symbol,
            'amount' => 1,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('from');
        $this->assertEquals(0, $this->toAccount->fresh()->balance);
    }

    public function test_to_currency_symbol_required()
    {
        $this->actingAs($this->user);

        $response = $this->postJson(route('exchange'), [
            'from' => $this->fromAccount->currency->symbol,
            'amount' => 1,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('to');
        $this->assertEquals(10, $this->fromAccount->fresh()->balance);
    }

    public function test_amount_can_not_be_negative()
    {
        $this->actingAs($this->user);

        $response = $this->postJson(route('exchange'), [
            'from' => $this->fromAccount->currency->symbol,
            'to' => $this->toAccount->currency->symbol,
            'amount' => -5,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('amount');
        $this->assertEquals(10, $this->fromAccount->fresh()->balance);
        $this->assertEquals(0, $this->toAccount->fresh()->balance);
    }

    public function test_a_currency_cant_be_exchanged_with_itself()
    {
        $this->actingAs($this->user);

        $response = $this->postJson(route('exchange'), [
            'from' => $this->fromAccount->currency->symbol,
            'to' => $this->fromAccount->currency->symbol,
            'amount' => 1,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('to');
    }

    public function test_user_cant_exchange_amount_higher_than_their_balance()
    {
        $this->actingAs($this->user);

        $response = $this->postJson(route('exchange'), [
            'from' => $this->fromAccount->currency->symbol,
            'to' => $this->toAccount->currency->symbol,
            'amount' => 100000,
        ]);

        $response->assertStatus(422);
        $this->assertStringContainsString('Insufficient balance', $response->json('message'));
    }

    public function test_unauthenticated_user_can_not_access()
    {
        $this->postJson(route('exchange'))->assertStatus(401);
    }
}
