<?php

namespace Tests\Feature;

use App\Exchange\Exceptions\InsufficientBalanceException;
use App\Exchange\Exceptions\NegativeAmountException;
use App\Exchange\Exchange;
use App\Exchange\Exchanger;
use App\Models\Account;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExchangerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Exchanger
     */
    private $exchanger;

    /**
     * @var Account
     */
    private $account1;

    /**
     * @var Account
     */
    private $account2;

    protected function setUp(): void
    {
        parent::setUp();

        $user = factory(User::class)->create();

        $currency1 = factory(Currency::class)->create([
            'btc_value' => 2
        ]);

        $currency2 = factory(Currency::class)->create([
            'btc_value' => 1
        ]);

        $this->account1 = $user->accounts()->create(factory(Account::class)->make([
            'currency_id' => $currency1->id,
            'balance' => 5,
        ])->toArray());

        $this->account2 = $user->accounts()->create(factory(Account::class)->make([
            'currency_id' => $currency2->id,
            'balance' => 1,
        ])->toArray());

        $this->exchanger = resolve(Exchanger::class);
    }

    public function test_exchange()
    {
        $this->exchanger->handle(new Exchange($this->account1, $this->account2, 1));

        $this->assertEquals(4, $this->account1->fresh()->balance);
        $this->assertEquals(2.995, $this->account2->fresh()->balance);
    }

    public function test_exchange_with_negative_amount()
    {
        $this->expectExceptionObject(new NegativeAmountException());
        $this->exchanger->handle(new Exchange($this->account1, $this->account2, -1999));
    }

    public function text_exchange_with_insufficient_balance()
    {
        $this->expectExceptionObject(new InsufficientBalanceException());
        $this->exchanger->handle(new Exchange($this->account1, $this->account2, 100000));
    }
}
