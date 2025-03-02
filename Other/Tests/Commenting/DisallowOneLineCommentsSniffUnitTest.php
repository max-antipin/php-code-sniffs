<?php

declare(strict_types=1);

namespace MaxAntipin\PHPCS\Standards\PSR12Ext\Tests\Commenting;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

class DisallowOneLineCommentsSniffUnitTest extends AbstractSniffUnitTest
{
    protected function getErrorList()
    {
        return [
            6 => 1,
            8 => 1,
        ];
    }

    protected function getWarningList()
    {
        return [];
    }
}
