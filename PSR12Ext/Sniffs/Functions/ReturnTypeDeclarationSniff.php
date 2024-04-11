<?php

/**
 * Ensure return types are defined for functions and closures.
 *
 * @author    Max Antipin <max.v.antipin@gmail.com>
 */

namespace MaxAntipin\PHPCS\Standards\PSR12Ext\Sniffs\Functions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class ReturnTypeDeclarationSniff implements Sniff
{
    public function register(): array
    {
        return [
            T_FUNCTION,
            T_CLOSURE,
            T_FN,
        ];
    }

    public function process(File $phpcsFile, $stackPtr): void
    {
        $tokens = $phpcsFile->getTokens();
        if (!isset($tokens[$stackPtr]['parenthesis_opener']) || !isset($tokens[$stackPtr]['parenthesis_closer'])) {
            return;
        }
        $methodProperties = $phpcsFile->getMethodProperties($stackPtr);
        if ($methodProperties['return_type'] === '') {
            $error = 'There must be a return type declaration';
            $phpcsFile->addError($error, $stackPtr, 'MissingReturnType');
        }
    }
}
