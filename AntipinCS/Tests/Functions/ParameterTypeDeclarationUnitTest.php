<?php

/**
 * Unit test class for the ParameterTypeDeclaration sniff.
 *
 * @author    Max Antipin <max.v.antipin@gmail.com>
 */

declare(strict_types=1);

namespace MaxAntipin\PHPCS\Standards\AntipinCS\Tests\Functions;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the ParameterTypeDeclaration sniff.
 *
 * @covers \MaxAntipin\PHPCS\Standards\AntipinCS\Sniffs\Functions\ParameterTypeDeclarationSniff
 */
final class ParameterTypeDeclarationUnitTest extends AbstractSniffUnitTest
{
    /**
     * Get a list of all test files to check.
     *
     * @param string $testFileBase The base path that the unit tests files will have.
     *
     * @return string[]
     */
    protected function getTestFiles($testFileBase): array
    {
        return array_map(static fn (int $i): string => $testFileBase . $i . '.inc', range(1, 4));
    }

    /**
     * @return array<int, int>
     */
    public function getErrorList(string $testFile = ''): array
    {
        return match ($testFile) {
            'ParameterTypeDeclarationUnitTest.1.inc' => [
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
                63  => 1,
                66  => 1,
            ],
            'ParameterTypeDeclarationUnitTest.2.inc' => [
                7 => 1,
            ],
            'ParameterTypeDeclarationUnitTest.3.inc' => [
                7 => 2,
            ],
            'ParameterTypeDeclarationUnitTest.4.inc' => [
                7 => 1,
                9 => 1,
            ],
            default => throw new \RuntimeException('Unhandled test file: ' . $testFile)
        };
    }

    /**
     * @return array<int, int>
     */
    public function getWarningList(): array
    {
        return [];
    }
}
