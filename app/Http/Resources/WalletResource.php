<?php

namespace App\Http\Resources;

use App\Exchange\Converter;
use App\Models\Account;
use App\Models\Currency;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class WalletResource extends JsonResource
{
    /**
     * @var Currency[]|\Illuminate\Database\Eloquent\Collection
     */
    private $currencies;

    /**
     * @var Converter
     */
    private $converter;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->currencies = Currency::all();
        $this->converter = resolve(Converter::class);
    }

    public function toArray($request)
    {
        return [
            'accounts' => $this->resource->map(function (Account $account) use ($request) {
                return array_merge((new AccountResource($account))->toArray($request), [
                    'values_in_others' => $this->calculateValuesInOtherCurrencies($account),
                ]);
            })
        ];
    }

    /**
     * Calculates values of the given account in other currencies
     *
     * @param Account $account
     * @return Collection
     */
    private function calculateValuesInOtherCurrencies(Account $account)
    {
        return $this->currencies
            ->filter(function (Currency $currency) use ($account) {
                return $currency->id != $account->currency->id;
            })
            ->map(function (Currency $currency) use ($account) {
                return [
                    'currency' => $currency->symbol,
                    'value' => format_float($this->converter->convert($account->currency, $currency, $account->balance)),
                ];
            })
            ->values();
    }
}
