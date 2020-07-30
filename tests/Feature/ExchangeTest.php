<?php

namespace Tests\Feature;

use App\Exchange\Exceptions\NegativeAmountException;
use App\Exchange\Exchange;
use App\Models\Account;
use Tests\TestCase;

class ExchangeTest extends TestCase
{
    public function test_fromAccount_returns_the_account_the_amount_will_be_taken()
    {
        $fromAccount = new Account();

        $exchange = new Exchange($fromAccount, new Account(), 1);

        $this->assertSame($fromAccount, $exchange->fromAccount());
    }

    public function test_toAccount_returns_the_account_the_converted_amount_will_be_put_to()
    {
        $toAccount = new Account();

        $exchange = new Exchange(new Account(), $toAccount, 1);

        $this->assertSame($toAccount, $exchange->toAccount());
    }

    public function test_amount_returns_the_amount_that_will_be_converted()
    {
        $exchange = new Exchange(new Account(), new Account(), 10);

        $this->assertEquals(10, $exchange->amount());
    }

    public function test_amount_can_not_be_negative()
    {
        $this->expectExceptionObject(new NegativeAmountException());

        new Exchange(new Account(), new Account(), -10);
    }
}
