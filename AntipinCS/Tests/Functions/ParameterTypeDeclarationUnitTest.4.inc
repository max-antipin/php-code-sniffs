<?php

namespace App\TestCase;

class MyFakeClass extends MyFakeInterface
{
    function doSomething($y): int
    {
        function MyFakeFunctionInside($j): int {
            return $j;
        }
        return MyFakeFunctionInside(3);
    }
}
