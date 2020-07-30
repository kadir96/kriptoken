<?php

namespace App\Exchange\Exceptions;

class NegativeAmountException extends \Exception
{
    public function __construct()
    {
        parent::__construct("An amount can not be negative!");
    }
}
