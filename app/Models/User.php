<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable implements JWTSubject
{
    protected $guarded = [];

    protected $hidden = [
        'password',
    ];

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    /**
     * @param Currency $currency
     * @return Account|Model
     */
    public function accountForCurrency(Currency $currency): Account
    {
        return $this->accounts()->firstOrCreate([
            'currency_id' => $currency->id,
        ]);
    }

    public function getJWTIdentifier(): string
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
