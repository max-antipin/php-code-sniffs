<?php

/**
 * Verifies that properties have type declaration.
 *
 * @author    Max Antipin <max.v.antipin@gmail.com>
 */

declare(strict_types=1);

namespace PHP_CodeSniffer\Standards\AntipinCS\Sniffs\Classes;

use Exception;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\AbstractVariableSniff;
use PHP_CodeSniffer\Util\Tokens;

class PropertyTypeDeclarationSniff extends AbstractVariableSniff
{
    protected function processMemberVar(File $phpcsFile, $stackPtr): void
    {
        $tokens = $phpcsFile->getTokens();

        // Detect multiple properties defined at the same time. Throw an error
        // for this, but also only process the first property in the list so we don't
        // repeat errors.
        $find   = Tokens::$scopeModifiers;
        $find[] = T_VARIABLE;
        $find[] = T_VAR;
        $find[] = T_READONLY;
        $find[] = T_SEMICOLON;
        $find[] = T_OPEN_CURLY_BRACKET;

        $prev = $phpcsFile->findPrevious($find, ($stackPtr - 1));
        if ($tokens[$prev]['code'] === T_VARIABLE) {
            return;
        }

        try {
            $propertyInfo = $phpcsFile->getMemberProperties($stackPtr);
            if (empty($propertyInfo)) {
                return;
            }
        } catch (Exception $e) {
            // Turns out not to be a property after all.
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
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file where this token was found.
     * @param int                         $stackPtr  The position where the token was found.
     *
     * @return void
     */
    protected function processVariable(File $phpcsFile, $stackPtr): void
    {
        /*
            We don't care about normal variables.
        */
    }

    /**
     * Processes variables in double quoted strings.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file where this token was found.
     * @param int                         $stackPtr  The position where the token was found.
     *
     * @return void
     */
    protected function processVariableInString(File $phpcsFile, $stackPtr): void
    {
        /*
            We don't care about normal variables.
        */
    }
}
