<?php

/**
 * Checks that arguments in function declarations has types.
 *
 * @author    Max Antipin <max.v.antipin@gmail.com>
 */

namespace MaxAntipin\PHPCS\Standards\PSR12Ext\Sniffs\Functions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class FunctionDeclarationArgumentTypeSniff implements Sniff
{
    public function register(): array
    {
        return [
            T_FUNCTION,
            T_CLOSURE,
            T_FN,
        ];
    }

    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $token = $tokens[$stackPtr];
        if (!isset($token['parenthesis_opener']) || !isset($token['parenthesis_closer'])) {
            $phpcsFile->addError(
                'Possible parse error: unable to find argument list. Checking has been aborted.',
                $stackPtr,
                'MissingParenthesis'
            );
            return;
        }
        $this->processBracket($phpcsFile, $token['parenthesis_opener']);
        if ($token['code'] === T_CLOSURE) {
            $use = $phpcsFile->findNext(T_USE, ($token['parenthesis_closer'] + 1), $token['scope_opener']);
            if ($use !== false) {
                $openBracket = $phpcsFile->findNext(T_OPEN_PARENTHESIS, ($use + 1), null);
                $this->processBracket($phpcsFile, $openBracket);
            }
        }
    }

    public function processBracket(File $phpcsFile, $openBracket)
    {
        $tokens = $phpcsFile->getTokens();
        if (isset($tokens[$openBracket]['parenthesis_owner'])) {
            $stackPtr = $tokens[$openBracket]['parenthesis_owner'];
        } else {
            $stackPtr = $phpcsFile->findPrevious(T_USE, ($openBracket - 1));
            if (false === $stackPtr) {
                $phpcsFile->addError(
                    'Unknown error',
                    $openBracket,
                    'Unknown',
                    [],
                    9
                );
            }
            # else: use condition found, skip it.
            return;
        }
        $params = $phpcsFile->getMethodParameters($stackPtr);
        if (empty($params)) {
            return;
        }
        foreach ($params as $param) {
            if ($param['type_hint_token'] === false) {
                if ($param['variable_length']) {
                    $phpcsFile->addWarning(
                        'Type hint missing for variable arguments "%s"',
                        $param['token'],
                        'MissingArgumentType',
                        [$param['name']],
                        5
                    );
                } else {
                    $phpcsFile->addError(
                        'Type hint missing for argument "%s"',
                        $param['token'],
                        'MissingArgumentType',
                        [$param['name']]
                    );
                }
            }
        }
    }
}
