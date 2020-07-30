<?php

/**
 * @return \App\Models\User|null
 */
function current_user()
{
    return auth()->user();
}

function format_float(float $float)
{
    $precision = config('exchange.decimal_precision');

    return sprintf("%.{$precision}f", $float);
}
