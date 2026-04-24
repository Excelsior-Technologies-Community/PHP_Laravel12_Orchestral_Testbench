<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\CalculatorService;

class CalculatorTest extends TestCase
{
    public function test_addition()
    {
        $calc = new \App\Services\CalculatorService();

        $result = $calc->add(2, 3);

        echo "\nAddition Result: " . $result . "\n";

        $this->assertEquals(5, $result);
    }

    public function test_multiplication()
    {
        $calc = new \App\Services\CalculatorService();

        $result = $calc->multiply(2, 3);

        fwrite(STDOUT, "\nMultiplication Test Passed: Result = $result\n");

        $this->assertEquals(6, $result);
    }
}
