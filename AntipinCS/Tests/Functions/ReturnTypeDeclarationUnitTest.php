<?php

/**
 * Unit test class for the ReturnTypeDeclaration sniff.
 *
 * @author    Max Antipin <max.v.antipin@gmail.com>
 */

declare(strict_types=1);

namespace MaxAntipin\PHPCS\Standards\AntipinCS\Tests\Functions;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * @covers \MaxAntipin\PHPCS\Standards\AntipinCS\Sniffs\Functions\ReturnTypeDeclarationSniff
 */
final class ReturnTypeDeclarationUnitTest extends AbstractSniffUnitTest
{
    /**
     * @return array<int, int>
     */
    protected function getErrorList(): array
    {
        return [
            65 => 1,
            68 => 1,
            73 => 1,
            75 => 1,
            77 => 1,
        ];
    }

    /**
     * @return array<int, int>
     */
    protected function getWarningList(): array
    {
        return [];
    }
}
