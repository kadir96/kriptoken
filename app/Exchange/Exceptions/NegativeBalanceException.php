<?php

namespace App\Exchange\Exceptions;

class NegativeBalanceException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Balance can not be negative!");
    }
}
