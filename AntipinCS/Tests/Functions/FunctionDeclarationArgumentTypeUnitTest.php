<?php

/**
 * Unit test class for the FunctionDeclarationArgumentType sniff.
 *
 * @author    Max Antipin <max.v.antipin@gmail.com>
 */

namespace MaxAntipin\PHPCS\Standards\AntipinCS\Tests\Functions;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the FunctionDeclarationArgumentType sniff.
 *
 * @covers \MaxAntipin\PHPCS\Standards\AntipinCS\Sniffs\Functions\FunctionDeclarationArgumentTypeSniff
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
            32  => 3,
            33  => 2,
            35  => 1,
            37  => 1,
            41  => 1,
            43  => 1,
            52  => 2,
            58  => 2,
            59  => 1,
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
