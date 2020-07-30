<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Currency;
use Faker\Generator as Faker;

$factory->define(Currency::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->firstName,
        'symbol' => $faker->unique()->lexify('???'),
        'btc_value' => $faker->randomFloat(config('exchange.decimal_precision'), 0.00000001, 1),
    ];
});
