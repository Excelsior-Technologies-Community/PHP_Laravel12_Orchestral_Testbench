<?php

namespace App\Services;

class CalculatorService
{
    public function add($a, $b)
    {
        return $a + $b;
    }

    public function multiply($a, $b)
    {
        return $a * $b;
    }
}