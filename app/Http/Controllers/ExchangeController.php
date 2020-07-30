<?php

namespace App\Http\Controllers;

use App\Exchange\Exceptions\InsufficientBalanceException;
use App\Exchange\Exchange;
use App\Exchange\Exchanger;
use App\Http\Requests\ExchangeRequest;
use App\Http\Resources\ExchangeResource;
use Throwable;

class ExchangeController extends Controller
{
    public function __invoke(ExchangeRequest $request, Exchanger $exchanger)
    {
        $exchange = new Exchange(
            current_user()->accountForCurrency($request->fromCurrency()),
            current_user()->accountForCurrency($request->toCurrency()),
            $request->amount
        );

        try {
            $exchanger->handle($exchange);
        } catch (InsufficientBalanceException $e) {
            abort(422, 'Insufficient balance!');
        } catch (Throwable $e) {
            report($e);

            abort(500, 'Exchange could not be made!');
        }

        return new ExchangeResource($exchange);
    }
}
