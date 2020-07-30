<?php

namespace App\Http\Requests;

use App\Models\Currency;
use Illuminate\Foundation\Http\FormRequest;

class ExchangeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'from' => 'required|string|exists:currencies,symbol',
            'to' => 'required|string|different:from|exists:currencies,symbol',
            'amount' => 'required|numeric|min:0.00000001',
        ];
    }

    public function messages()
    {
        return [
            'to.different' => 'A currency is only exchangeable with another currency.',
        ];
    }

    public function fromCurrency()
    {
        return Currency::bySymbol($this->get('from'));
    }

    public function toCurrency()
    {
        return Currency::bySymbol($this->get('to'));
    }
}
