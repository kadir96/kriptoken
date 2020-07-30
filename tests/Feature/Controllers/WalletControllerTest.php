<?php

namespace Tests\Feature\Controllers;

use App\Exchange\Converter;
use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WalletControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var User
     */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->user->accounts()->createMany(factory(Account::class, 2)->make()->toArray());
    }

    public function test_get_accounts_data_successfully()
    {
        $this->actingAs($this->user);
        $response = $this->getJson(route('wallet'));

        $account1 = $this->user->accounts[0];
        $account2 = $this->user->accounts[1];

        $converter = resolve(Converter::class);

        $response->assertJsonFragment([
            'accounts' => [
                [
                    'id' => $account1->id,
                    'balance' => $account1->formatted_balance,
                    'currency' => [
                        'id' => $account1->currency->id,
                        'name' => $account1->currency->name,
                        'symbol' => $account1->currency->symbol,
                        'btc_value' => $account1->currency->formatted_btc_value,
                    ],
                    'values_in_others' => [
                        [
                            'currency' => $account2->currency->symbol,
                            'value' => format_float($converter->convert($account1->currency, $account2->currency, $account1->balance)),
                        ]
                    ],
                ],
                [
                    'id' => $account2->id,
                    'balance' => $account2->formatted_balance,
                    'currency' => [
                        'id' => $account2->currency->id,
                        'name' => $account2->currency->name,
                        'symbol' => $account2->currency->symbol,
                        'btc_value' => $account2->currency->formatted_btc_value,
                    ],
                    'values_in_others' => [
                        [
                            'currency' => $account1->currency->symbol,
                            'value' => format_float($converter->convert($account2->currency, $account1->currency, $account2->balance)),
                        ]
                    ],
                ],
            ]
        ]);
    }

    public function test_unauthenticated_user_can_not_access()
    {
        $this->getJson(route('wallet'))->assertStatus(401);
    }
}
