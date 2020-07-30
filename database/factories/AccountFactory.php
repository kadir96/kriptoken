<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Account;
use App\Models\User;
use App\Models\Currency;
use Faker\Generator as Faker;

$factory->define(Account::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'currency_id' => factory(Currency::class),
        'balance' => $faker->randomFloat(config('exchange.decimal_precision'), 0, 10000),
    ];
});
