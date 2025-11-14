<?php

/**
 * Checks that arguments in function declarations have types.
 *
 * @author    Max Antipin <max.v.antipin@gmail.com>
 */

declare(strict_types=1);

namespace MaxAntipin\PHPCS\Standards\AntipinCS\Sniffs\Functions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use ValueError;

class ParameterTypeDeclarationSniff implements Sniff
{
    /**
     * A list of functions and methods to ignore.
     *
     * This sniff can cause an error if you're overriding a parent method
     * or implementing an interface which does not have typehints.
     *
     * @var array<string, string>
     */
    public array $ignore = [];

    /**
     * @var array<string, array<string, string>> $itemsToIgnore
     */
    private ?array $itemsToIgnore = null;

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
                if (false === $openBracket) {
                    throw new \RuntimeException('Parse error');
                }
                $this->processBracket($phpcsFile, $openBracket);
            }
        }
    }

    protected function processBracket(File $phpcsFile, int $openBracket): void
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
                if ($this->ignoreMethod($phpcsFile, $stackPtr)) {
                    break;
                }
                $phpcsFile->addError(
                    'Type hint missing for ' . ($param['variable_length'] ? 'variadic ' : '') . 'parameter "%s"',
                    $param['token'],
                    'MissingParameterType',
                    [$param['name']]
                );
            } elseif ($param['type_hint'] === 'mixed') {
                $phpcsFile->addError(
                    'Incorrect type hint for ' . ($param['variable_length'] ? 'variadic ' : '') . 'parameter "%s"',
                    $param['token'],
                    'IncorrectParameterType',
                    [$param['content']]
                );
            }
        }
    }

    private function ignoreMethod(File $phpcsFile, int $stackPtr): bool
    {
        $tokens = $phpcsFile->getTokens();
        $fToken = $tokens[$stackPtr];
        if (
            $this->itemsToIgnore === []
            // Anonymous or arrow functions can not be ignored.
            || $fToken['type'] !== 'T_FUNCTION'
            || false === ($cStackPtr = $phpcsFile->findPrevious(T_CLASS, $stackPtr))
            || ($cToken = $tokens[$cStackPtr]) && ($cToken['level'] - $fToken['level'] > 1)
            || !($names = $this->getParentNames($phpcsFile, $cStackPtr))
        ) {
            return false;
        }
        if ($this->itemsToIgnore === null) {
            $this->itemsToIgnore = [];
            foreach ($this->ignore as $class => $methods) {
                if (!preg_match('/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$/', $class)) {
                    throw new ValueError();// todo: !!!
                }
                foreach (explode(',', $methods) as $method) {
                    $method = trim($method);
                    if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $method)) {
                        throw new ValueError();// todo: !!!
                    }
                    $this->itemsToIgnore[$class][$method] = $method;
                }
                if (empty($this->itemsToIgnore[$class])) {
                    throw new ValueError();// todo: error message
                }
            }
        }
        $declarationName = $phpcsFile->getDeclarationName($stackPtr);
        foreach ($names as $name) {
            if (isset($this->itemsToIgnore[$name][$declarationName])) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param \PHP_CodeSniffer\Files\File $phpcsFile
     * @param int $cStackPtr
     * @return array<int, string>
     */
    private function getParentNames(File $phpcsFile, int $cStackPtr): array
    {
        $names = $phpcsFile->findImplementedInterfaceNames($cStackPtr) ?: [];
        if ($extends = $phpcsFile->findExtendedClassName($cStackPtr)) {
            $names[] = $extends;
        }
        foreach ($names as $name) {
            if ($name[0] !== '\\') {
                // todo: get FQCN!!!
            }
        }
        return $names;
    }
}
