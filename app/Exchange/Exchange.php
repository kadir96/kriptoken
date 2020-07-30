<?php

namespace App\Exchange;

use App\Exchange\Exceptions\NegativeAmountException;
use App\Models\Account;

class Exchange
{
    /**
     * The account that the amount will be taken from.
     *
     * @var Account
     */
    private $fromAccount;

    /**
     * The account that will have the exchanged amount.
     *
     * @var Account
     */
    private $toAccount;

    /**
     * The amount to exchange.
     *
     * @var float
     */
    private $amount;

    /**
     * Exchange constructor.
     *
     * @param Account $fromAccount
     * @param Account $toAccount
     * @param string $amount
     * @throws NegativeAmountException
     */
    public function __construct(Account $fromAccount, Account $toAccount, float $amount)
    {
        if ($amount < 0) {
            throw new NegativeAmountException();
        }

        $this->fromAccount = $fromAccount;
        $this->toAccount = $toAccount;
        $this->amount = $amount;
    }

    public function fromAccount()
    {
        return $this->fromAccount;
    }

    public function toAccount()
    {
        return $this->toAccount;
    }

    public function amount()
    {
        return $this->amount;
    }
}
