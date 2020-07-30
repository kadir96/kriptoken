<?php

namespace Tests\Feature\Models;

use App\Exchange\Exceptions\InsufficientBalanceException;
use App\Exchange\Exceptions\NegativeBalanceException;
use App\Models\Account;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_belongs_to_user()
    {
        $user = factory(User::class)->create();
        $account = factory(Account::class)->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $account->user);
        $this->assertEquals($user->id, $account->user->id);
    }

    public function test_belongs_to_currency()
    {
        $currency = factory(Currency::class)->create();
        $account = factory(Account::class)->create([
            'currency_id' => $currency->id,
        ]);

        $this->assertInstanceOf(Currency::class, $account->currency);
        $this->assertEquals($currency->id, $account->currency->id);
    }

    public function test_AddBalance_successfully()
    {
        $account = factory(Account::class)->create([
            'balance' => 5,
        ]);

        $account->addBalance(2.67);

        $this->assertEquals(7.67, $account->balance);
    }

    public function test_subBalance_successfully()
    {
        $account = factory(Account::class)->create([
            'balance' => 5,
        ]);

        $account->subBalance(3);

        $this->assertEquals(2, $account->balance);
    }

    public function test_subBalance_higher_amount_than_current_balance()
    {
        $this->expectExceptionObject(new InsufficientBalanceException());

        $account = factory(Account::class)->create([
            'balance' => 5,
        ]);

        $account->subBalance(100);
    }

    public function test_balance_can_not_be_negative()
    {
        $this->expectExceptionObject(new NegativeBalanceException());

        $account = factory(Account::class)->create([
            'balance' => 5,
        ]);

        $account->balance = -100;
    }

    public function test_formatted_btc_value()
    {
        $account = factory(Account::class)->make([
            'balance' => 0.3
        ]);

        $this->assertEquals(format_float($account->balance), $account->formatted_balance);
    }
}
