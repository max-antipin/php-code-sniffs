<?php

/**
 * Unit test class for the FunctionDeclarationArgumentType sniff.
 *
 * @author    Max Antipin <max.v.antipin@gmail.com>
 */

namespace MaxAntipin\PHPCS\Standards\PSR12Ext\Tests\Functions;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the FunctionDeclarationArgumentType sniff.
 *
 * @covers \MaxAntipin\PHPCS\Standards\PSR12Ext\Sniffs\Functions\FunctionDeclarationArgumentTypeSniff
 */
final class FunctionDeclarationArgumentTypeUnitTest extends AbstractSniffUnitTest
{
    /**
     * @return array<int, int>
     */
    public function getErrorList()
    {
        return [
            4   => 1,
            5   => 3,
            6   => 2,
            7   => 1,
            9   => 1,
            10  => 1,
            11  => 3,
            12  => 1,
            13  => 1,
            15  => 1,
            19  => 1,
            21  => 1,
            25  => 2,
            32  => 2,
            33  => 1,
            35  => 2,
            46  => 1,
            51  => 2,
            53  => 2,
            55  => 1,
            56  => 1,
            58  => 1,
            73  => 7,
            76  => 1,
            77  => 1,
            81  => 1,
            89  => 2,
            92  => 1,
            93  => 1,
            94  => 1,
            95  => 1,
            99  => 11,
            100 => 2,
            101 => 2,
            102 => 2,
            106 => 1,
            107 => 2,
            111 => 2,
        ];
    }

    /**
     * @return array<int, int>
     */
    public function getWarningList()
    {
        return [
            32  => 1,
            33  => 1,
            37  => 1,
        ];
    }
}
