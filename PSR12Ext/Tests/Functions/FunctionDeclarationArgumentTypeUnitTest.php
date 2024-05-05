<?php

/**
 * Unit test class for the FunctionDeclarationArgumentType sniff.
 *
 * @author    Max Antipin <max.v.antipin@gmail.com>
 */

namespace PHP_CodeSniffer\Standards\Squiz\Tests\Functions;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the FunctionDeclarationArgumentType sniff.
 *
 * @covers \PHP_CodeSniffer\Standards\Squiz\Sniffs\Functions\FunctionDeclarationArgumentTypeSniff
 */
final class FunctionDeclarationArgumentTypeUnitTest extends AbstractSniffUnitTest
{
    /**
     * @return array<int, int>
     */
    public function getErrorList()
    {
        return [
            3   => 1,
            5   => 2,
            7   => 2,
            8   => 2,
            9   => 2,
            11  => 2,
            13  => 7,
            14  => 2,
            15  => 2,
            16  => 4,
            18  => 2,
            35  => 2,
            36  => 2,
            44  => 2,
            45  => 1,
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
            111 => 3,
        ];
    }

    /**
     * @return array<int, int>
     */
    public function getWarningList()
    {
        return [];
    }
}
