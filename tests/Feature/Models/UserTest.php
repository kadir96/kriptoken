<?php

namespace Tests\Feature\Models;

use App\Models\Account;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var User
     */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();;
    }

    public function test_has_many_accounts()
    {

        $this->assertEquals(0, $this->user->accounts()->count());

        factory(Account::class, 2)->create([
            'user_id' => $this->user->id,
        ]);

        $this->assertEquals(2, $this->user->accounts()->count());
    }

    public function test_AccountForCurrency_creates_new_account_for_currency_if_there_is_no_account_related()
    {
        $currency = factory(Currency::class)->create();
        $this->assertNull($this->user->accounts()->whereCurrencyId($currency->id)->first());

        $account = $this->user->accountForCurrency($currency);

        $this->assertTrue($account->wasRecentlyCreated);
        $this->assertEquals($currency->id, $account->currency->id);
    }

    public function test_AccountForCurrency_returns_account_if_already_an_account_exists_for_currency()
    {
        $currency = factory(Currency::class)->create();

        $account = $this->user->accountForCurrency($currency);

        $this->assertEquals($account->id, $this->user->accountForCurrency($currency)->id);
    }
}
