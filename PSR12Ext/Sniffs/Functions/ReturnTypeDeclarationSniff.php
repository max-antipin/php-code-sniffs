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
        $token = $tokens[$stackPtr];
        if (!isset($token['parenthesis_opener']) || !isset($token['parenthesis_closer'])) {
            $phpcsFile->addError(
                'Unable to find argument list; checking has been aborted.',
                $stackPtr,
                'MissingParenthesis'
            );
            return;
        }
        $methodProperties = $phpcsFile->getMethodProperties($stackPtr);
        if ($methodProperties['return_type'] === '') {
            $phpcsFile->addError(
                'There must be a return type declaration',
                $stackPtr,
                'MissingReturnType'
            );
        }
    }
}
