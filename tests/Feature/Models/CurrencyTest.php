<?php

namespace Tests\Feature\Models;

use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CurrencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_bySymbol_returns_currency_by_symbol()
    {
        $existing = factory(Currency::class)->create();

        $currency = Currency::bySymbol($existing->symbol);

        $this->assertInstanceOf(Currency::class, $currency);
        $this->assertEquals($existing->id, $currency->id);
    }

    public function test_symbols_got_uppercased()
    {
        $currency = factory(Currency::class)->make();

        $currency->symbol = 'abc';

        $this->assertEquals('ABC', $currency->symbol);
    }

    public function test_formatted_btc_value()
    {
        $currency = factory(Currency::class)->make([
            'btc_value' => 0.3
        ]);

        $this->assertEquals(format_float($currency->btc_value), $currency->formatted_btc_value);
    }
}
