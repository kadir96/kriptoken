<?php

namespace App\Models;

use App\Exchange\Exceptions\InsufficientBalanceException;
use App\Exchange\Exceptions\NegativeBalanceException;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $with = ['currency'];

    protected $attributes = [
        'balance' => 0,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * @param float $amount
     * @return self
     */
    public function addBalance(float $amount)
    {
        $this->balance = bcadd(format_float($this->balance), format_float($amount));

        return $this;
    }

    /**
     * @param float $amount
     * @return self
     * @throws InsufficientBalanceException
     */
    public function subBalance(float $amount)
    {
        if ($this->balance < $amount) {
            throw new InsufficientBalanceException();
        }

        $this->balance = bcsub(format_float($this->balance), format_float($amount));

        return $this;
    }

    /**
     * @param float $balance
     * @throws NegativeBalanceException
     */
    public function setBalanceAttribute(float $balance)
    {
        if ($balance < 0) {
            throw new NegativeBalanceException();
        }

        $this->attributes['balance'] = $balance;
    }

    public function getFormattedBalanceAttribute()
    {
        return format_float($this->balance);
    }
}
