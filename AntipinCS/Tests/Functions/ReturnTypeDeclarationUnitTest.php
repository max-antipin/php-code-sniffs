<?php

/**
 * Unit test class for the ReturnTypeDeclaration sniff.
 *
 * @author    Max Antipin <max.v.antipin@gmail.com>
 */

declare(strict_types=1);

namespace MaxAntipin\PHPCS\Standards\AntipinCS\Tests\Functions;

use MaxAntipin\PHPCS\Standards\AntipinCS\Sniffs\Functions\ReturnTypeDeclarationSniff;
use PHP_CodeSniffer\Tests\Standards\AbstractSniffTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ReturnTypeDeclarationSniff::class)]
final class ReturnTypeDeclarationUnitTest extends AbstractSniffTestCase
{
    /**
     * Get a list of all test files to check.
     *
     * @param string $testFileBase The base path that the unit tests files will have.
     *
     * @return string[]
     */
    protected function getTestFiles(string $testFileBase): array
    {
        return array_map(static fn (int $i): string => $testFileBase . $i . '.inc', range(1, 2));
    }

    /**
     * @return array<int, int>
     */
    protected function getErrorList(string $testFile = ''): array
    {
        return match ($testFile) {
            'ReturnTypeDeclarationUnitTest.1.inc' => [
                65 => 1,
                68 => 1,
                73 => 1,
                75 => 1,
                77 => 1,
            ],
            'ReturnTypeDeclarationUnitTest.2.inc' => [],
            default => throw new \RuntimeException('Unhandled test file: ' . $testFile)
        };
    }

    /**
     * @return array<int, int>
     */
    protected function getWarningList(): array
    {
        return [];
    }
}
