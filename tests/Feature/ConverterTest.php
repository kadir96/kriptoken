<?php

namespace Tests\Feature;

use App\Exchange\Converter;
use App\Exchange\Exceptions\NegativeAmountException;
use Tests\TestCase;
use App\Models\Currency;

class ConverterTest extends TestCase
{
    /**
     * @var Converter
     */
    private $converter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->converter = new Converter(0);
    }

    public function test_convert()
    {
        $this->assertEquals(
            200,
            $this->converter->convert(new Currency(['btc_value' => 2]), new Currency(['btc_value' => 1]), 100),
        );
    }

    public function test_amount_can_not_be_negative()
    {
        $this->expectExceptionObject(new NegativeAmountException());

        $this->converter->convert(new Currency(), new Currency(), -9999);
    }
}
