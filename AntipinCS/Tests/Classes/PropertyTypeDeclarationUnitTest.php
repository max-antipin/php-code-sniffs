<?php
/**
 * Unit test class for the PropertyTypeDeclaration sniff.
 *
 * @author    Max Antipin <max.v.antipin@gmail.com>
 */

namespace PHP_CodeSniffer\Standards\AntipinCS\Tests\Classes;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the PropertyTypeDeclaration sniff.
 *
 * @covers \PHP_CodeSniffer\Standards\AntipinCS\Sniffs\Classes\PropertyTypeDeclarationSniff
 */
final class PropertyTypeDeclarationUnitTest extends AbstractSniffUnitTest
{
    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @return array<int, int>
     */
    public function getErrorList()
    {
        return [
            7  => 1,
            9  => 2,
            10 => 1,
            11 => 1,
            17 => 1,
            18 => 1,
            23 => 1,
            38 => 1,
            41 => 1,
            42 => 1,
            50 => 2,
            51 => 1,
            55 => 1,
            56 => 1,
            61 => 1,
            62 => 1,
            68 => 1,
            69 => 1,
            71 => 1,
            72 => 1,
            76 => 1,
            80 => 1,
            82 => 1,
            84 => 1,
            86 => 1,
        ];

    }

    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @return array<int, int>
     */
    public function getWarningList()
    {
        return [
            13 => 1,
            14 => 1,
            15 => 1,
            53 => 1,
        ];

    }
}
