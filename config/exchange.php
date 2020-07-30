<?php

use App\Models\Currency;

return [
    /*
     * Commission percent to be deducted from amount when exchanging.
     */
    'commission_percent' => 0.25,

    /*
     * The decimal precision used for conversions.
     */
    'decimal_precision' => 8,

    /*
     * The exchange rates between currencies.
     *
     * We only store BTC-to-others and others-to-BTC rates
     * as the conversions are done by firstly converting the base currency to BTC
     * and then converting BTC to requested one.
     */
    'rates' => [
        // BTC to others
        [
            'from' => 'BTC',
            'to' => 'ETH',
            'rate' => '20.87067880',
        ],
        [
            'from' => 'BTC',
            'to' => 'LTC',
            'rate' => '168.67530846',
        ],
        [
            'from' => 'BTC',
            'to' => 'DASH',
            'rate' => '14.80031708',
        ],
        [
            'from' => 'BTC',
            'to' => 'XRP',
            'rate' => '49975.01249375',
        ],

        // Others to BTC
        [
            'from' => 'ETH',
            'to' => 'BTC',
            'rate' => '0.04791411',
        ],
        [
            'from' => 'LTC',
            'to' => 'BTC',
            'rate' => '0.00592855',
        ],
        [
            'from' => 'DASH',
            'to' => 'BTC',
            'rate' => '0.06756612',
        ],
        [
            'from' => 'XRP',
            'to' => 'BTC',
            'rate' => '0.00002001',
        ],
    ]
];
