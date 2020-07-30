<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExchangeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'accounts' => [
                'from' => new AccountResource($this->fromAccount()),
                'to' => new AccountResource($this->toAccount()),
            ],
        ];
    }
}
