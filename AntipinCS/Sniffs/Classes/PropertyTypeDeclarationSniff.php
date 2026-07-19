<?php

/**
 * Verifies that properties have type declaration.
 *
 * @author    Max Antipin <max.v.antipin@gmail.com>
 */

declare(strict_types=1);

namespace MaxAntipin\PHPCS\Standards\AntipinCS\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\AbstractVariableSniff;
use PHP_CodeSniffer\Util\Tokens;

class PropertyTypeDeclarationSniff extends AbstractVariableSniff
{
    protected function processMemberVar(File $phpcsFile, int $stackPtr): void
    {
        $tokens = $phpcsFile->getTokens();

        // Detect multiple properties defined at the same time. Throw an error
        // for this, but also only process the first property in the list so we don't
        // repeat errors.
        static $find = [...Tokens::SCOPE_MODIFIERS,
            T_VARIABLE,
            T_VAR,
            T_READONLY,
            T_FINAL,
            T_ABSTRACT,
            T_SEMICOLON,
            T_OPEN_CURLY_BRACKET,
        ];
        $prevPtr = $phpcsFile->findPrevious($find, ($stackPtr - 1));
        if ($tokens[$prevPtr]['code'] === T_VARIABLE) {
            return;
        }
        if ($tokens[$prevPtr]['code'] === T_OPEN_CURLY_BRACKET) {
            $prevPtr = $phpcsFile->findPrevious(Tokens::EMPTY_TOKENS, $prevPtr - 1, null, true);
            if ($tokens[$prevPtr]['content'] === 'get') {
                return;
            }
        }
        $nextPtr = $phpcsFile->findNext(Tokens::EMPTY_TOKENS, $stackPtr + 1, null, true);
        static $exclude = [T_OBJECT_OPERATOR];
        if (
            in_array($tokens[$nextPtr]['code'], $exclude, true) ||
            !($propertyInfo = $phpcsFile->getMemberProperties($stackPtr))
        ) {
            return;
        }
        $property = $tokens[$stackPtr]['content'];
        if ($propertyInfo['type'] === '') {
            // todo: we can not use callable type for properties
            $phpcsFile->addError(
                sprintf('Missing type declaration for property "%s"', $property),
                $stackPtr,
                'MissingPropertyType'
            );
        } else {
            // todo: if we use an array type we must add doc-comment @property array<int, string> $propName
        }
    }

    /**
     * Processes normal variables.
     *
     * @param File $phpcsFile The file where this token was found.
     * @param int                         $stackPtr  The position where the token was found.
     *
     * @return void
     */
    protected function processVariable(File $phpcsFile, int $stackPtr): void
    {
        /*
            We don't care about normal variables.
        */
    }

    /**
     * Processes variables in double quoted strings.
     *
     * @param File $phpcsFile The file where this token was found.
     * @param int                         $stackPtr  The position where the token was found.
     *
     * @return void
     */
    protected function processVariableInString(File $phpcsFile, int $stackPtr): void
    {
        /*
            We don't care about normal variables.
        */
    }
}
