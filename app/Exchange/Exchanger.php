<?php

namespace App\Exchange;

use Illuminate\Support\Facades\DB;
use Throwable;

class Exchanger
{
    /**
     * @var Converter
     */
    private $converter;

    /**
     * @var float
     */
    private $commissionPercent;

    public function __construct(Converter $converter, float $commissionPercent)
    {
        $this->converter = $converter;
        $this->commissionPercent = $commissionPercent;
    }

    /**
     * Handles the given exchange
     *
     * @param Exchange $exchange
     * @throws Throwable
     * @return void
     */
    public function handle(Exchange $exchange)
    {
        // Since we change multiple records in an exchange
        // We need to use transaction to be able to rollback
        // everything if an error occurs along the way
        DB::beginTransaction();

        try {
            $exchange->fromAccount()->refresh()->subBalance($exchange->amount())->saveOrFail();

            $amountInRequestedCurrency = $this->converter->convert(
                $exchange->fromAccount()->currency,
                $exchange->toAccount()->currency,
                $this->calculateCommissionDeductedAmount($exchange->amount()),
            );

            $exchange->toAccount()->refresh()->addBalance($amountInRequestedCurrency)->saveOrFail();
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();
    }

    /**
     * Calculate the commission deducted amount for conversion.
     *
     * @param string $amount
     * @return string
     */
    private function calculateCommissionDeductedAmount(string $amount)
    {
        return bcsub($amount, bcmul($amount, $this->commissionPercent / 100));
    }
}
