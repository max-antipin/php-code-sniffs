<?php

namespace App\TestCase;

trait MyFakeTrait
{
    function doNothing($a, $b): int {
        return $a * $b;
    }
}
