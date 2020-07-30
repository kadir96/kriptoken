<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    /**
     * Get currency by symbol
     *
     * @param string $symbol
     *
     * @return self|null
     */
    public static function bySymbol(string $symbol)
    {
        return self::whereSymbol($symbol)->first();
    }

    public function setSymbolAttribute($val)
    {
        $this->attributes['symbol'] = strtoupper($val);
    }

    public function getFormattedBtcValueAttribute()
    {
        return format_float($this->btc_value);
    }
}
