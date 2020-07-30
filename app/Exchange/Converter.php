<?php

namespace App\Exchange;

use App\Exchange\Exceptions\NegativeAmountException;
use App\Models\Currency;

class Converter
{
    /**
     * @var float
     */
    private $decimalPrecision;

    public function __construct(float $decimalPrecision)
    {
        $this->decimalPrecision = $decimalPrecision;
    }

    /**
     * Converts the amount in the given currency to requested one.
     *
     * @param Currency $from
     * @param Currency $to
     * @param float $amount
     *
     * @return float
     * @throws NegativeAmountException
     */
    public function convert(Currency $from, Currency $to, float $amount)
    {
        if($amount < 0) {
            throw new NegativeAmountException();
        }

        // We first convert `from` -> BTC
        // And then convert BTC -> `to`
        $asBtc = bcmul(format_float($amount), $from->btc_value);

        return (float) bcdiv($asBtc, $to->btc_value);
    }
}
