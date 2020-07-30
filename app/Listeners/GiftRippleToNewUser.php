<?php

namespace App\Listeners;

use App\Models\Currency;
use Illuminate\Auth\Events\Registered;

class GiftRippleToNewUser
{
    const AMOUNT_TO_GIFT = 10000;

    public function handle(Registered $event)
    {
        // Gift the user XRP by creating a new XRP account for them and setting its balance to gifted amount.
        $account = $event->user->accountForCurrency(Currency::bySymbol('XRP'));

        $account
            ->addBalance(self::AMOUNT_TO_GIFT)
            ->save();
    }
}
