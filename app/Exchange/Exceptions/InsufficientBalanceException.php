<?php

namespace App\Exchange\Exceptions;

class InsufficientBalanceException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Can not make the exchange as the balance of the account is insufficient!");
    }
}
