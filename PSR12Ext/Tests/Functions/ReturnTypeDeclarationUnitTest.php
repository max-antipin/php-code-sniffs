<?php

/**
 * Unit test class for the ReturnTypeDeclaration sniff.
 *
 * @author    Max Antipin <max.v.antipin@gmail.com>
 */

declare(strict_types=1);

namespace MaxAntipin\PHPCS\Standards\PSR12Ext\Tests\Functions;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * @covers \MaxAntipin\PHPCS\Standards\PSR12Ext\Sniffs\Functions\ReturnTypeDeclarationSniff
 */
final class ReturnTypeDeclarationUnitTest extends AbstractSniffUnitTest
{
    /**
     * @return array<int, int>
     */
    protected function getErrorList(): array
    {
        return [
            68 => 1,
            73 => 1,
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
