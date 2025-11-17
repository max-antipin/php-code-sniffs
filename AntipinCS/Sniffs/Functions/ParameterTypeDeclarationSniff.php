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
use PHP_CodeSniffer\Util\Tokens;
use RuntimeException;

class ParameterTypeDeclarationSniff implements Sniff, \JsonSerializable
{
    private const RX_FQCN =
        '/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*(\\\\[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)+$/';

    public function jsonSerialize(): array
    {
        return [];
    }
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
                $openBracket = $phpcsFile->findNext(T_OPEN_PARENTHESIS, ($use + 1));
                if (false === $openBracket) {
                    throw new RuntimeException('Parse error');
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
            // Private methods can not be ignored.
            || $phpcsFile->getMethodProperties($stackPtr)['scope'] === 'private'
            || ($cToken = $tokens[$cStackPtr]) && ($cToken['level'] - $fToken['level'] > 1)
            || !($names = $this->getParentNames($phpcsFile, $cStackPtr))
        ) {
            return false;
        }
        if ($this->itemsToIgnore === null) {
            $this->itemsToIgnore = [];
            foreach ($this->ignore as $class => $methods) {
                if (!preg_match(self::RX_FQCN, $class)) {
                    throw new RuntimeException('Config error: invalid class or interface name: ' . $class);
                }
                foreach (explode(',', $methods) as $method) {
                    $method = trim($method);
                    if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $method)) {
                        throw new RuntimeException("Config error: invalid method name: $class::$method()");
                    }
                    $this->itemsToIgnore[$class][$method] = $method;
                }
                if (empty($this->itemsToIgnore[$class])) {
                    throw new RuntimeException('Config error: no methods defined for ' . $class);
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
     * Handles these cases:
     * use ValueError;
     * use \ErrorException;
     * use PHP_CodeSniffer\Files\File;
     * use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest as SomeKindOfTests;
     * use PHPstan\Command\{AnalyseCommand,AnalyserResult as TmpResult,AnalyseApplication\TestApp};
     * use function file_get_contents;
     * use const JSON_BIGINT_AS_STRING;
     *
     * @return array<string, string>
     */
    protected function findClassImports(File $phpcsFile, ?int $limitPtr = null): array
    {
        $tokens = $phpcsFile->getTokens();
        $imports = [];
        $addImport = static function (string $alias, string $fqcn) use (&$imports): void {
            if (isset($imports[$alias])) {
                throw new RuntimeException('Duplicate alias');
            }
            $imports[$alias] = $fqcn;
        };
        $getTokenAs = static function (File $phpcsFile, int $startPtr, int $endPtr): int {
            if (
                false !== ($ptr = $phpcsFile->findPrevious(T_AS, $endPtr - 1, $startPtr))
                && false !== ($ptr = $phpcsFile->findPrevious(T_STRING, $ptr - 1, $startPtr))
            ) {
                return $ptr;
            }
            return $endPtr;
        };
        $trimStringTokens = static function (File $phpcsFile, int &$startPtr, int &$endPtr): void {
            $tokens = $phpcsFile->getTokens();
            if ($tokens[$startPtr]['code'] !== T_STRING) {
                $ptr = $phpcsFile->findNext(T_STRING, $startPtr, $endPtr);
                if (false === $ptr) {
                    throw new RuntimeException('Parse error');
                }
                $startPtr = $ptr;
            }
            if ($tokens[$endPtr]['code'] !== T_STRING) {
                $ptr = $phpcsFile->findPrevious(T_STRING, $endPtr, $startPtr);
                if (false === $ptr) {
                    throw new RuntimeException('Parse error');
                }
                $endPtr = $ptr;
            }
        };
        $start = 1;
        static $skip = ['function' => 1, 'const' => 1];
        while ($startPtr = $phpcsFile->findNext(T_USE, $start, $limitPtr)) {
            $endPtr = $phpcsFile->findEndOfStatement($startPtr);
            // Make sure this is not a closure USE group.
            $ptr = $phpcsFile->findNext(Tokens::$emptyTokens, $startPtr + 1, $endPtr, true);
            if (false === $ptr || $tokens[$ptr]['code'] === T_OPEN_PARENTHESIS) {
                continue;
            }
            if ($phpcsFile->hasCondition($startPtr, Tokens::$ooScopeTokens) === true) {
                // Import statements inside classes (use Something;).
                break;
            }
            $startPtr = $ptr;
            $start = $endPtr + 1;
            if ($tokens[$startPtr]['code'] === T_STRING && isset($skip[strtolower($tokens[$startPtr]['content'])])) {
                continue;
            }
            if (false !== ($ptr = $phpcsFile->findPrevious(Tokens::$emptyTokens, $endPtr - 1, $startPtr, true))) {
                $endPtr = $ptr;
            }
            if ('PHPCS_T_CLOSE_USE_GROUP' === $tokens[$endPtr]['code']) {
                $ptr = $phpcsFile->findPrevious(T_OPEN_USE_GROUP, $endPtr, $startPtr + 1);
                if (false === $ptr) {
                    throw new RuntimeException('Parse error');
                }
                $base = $phpcsFile->getTokensAsString($startPtr, $ptr - $startPtr);
                foreach (
                    (static function (
                        File $phpcsFile,
                        int $startPtr,
                        int $limitPtr,
                    ) use (
                        $getTokenAs,
                        $trimStringTokens
                    ): \Generator {
                        $tokens = $phpcsFile->getTokens();
                        $endPtr = $limitPtr;
                        $trimStringTokens($phpcsFile, $startPtr, $endPtr);
                        // Foo as Bar - at least 5 tokens;
                        // so if the number of tokens is less than 5, it's not an 'as' statement.
                        if ($endPtr - $startPtr >= 4) {
                            while (false !== ($ptr = $phpcsFile->findNext(T_COMMA, $startPtr, $endPtr))) {
                                yield from (static function (
                                    File $phpcsFile,
                                    int $startPtr,
                                    int $endPtr,
                                ) use (
                                    $getTokenAs,
                                    $trimStringTokens
                                ): \Generator {
                                    $trimStringTokens($phpcsFile, $startPtr, $endPtr);
                                    $tokens = $phpcsFile->getTokens();
                                    $alias = $tokens[$endPtr]['content'];
                                    $endPtr = $getTokenAs($phpcsFile, $startPtr, $endPtr);
                                    yield $alias => $phpcsFile->getTokensAsString(
                                        $startPtr,
                                        $endPtr - $startPtr + 1
                                    );
                                })($phpcsFile, $startPtr, $ptr - 1);
                                $startPtr = $ptr + 1;
                            }
                            $alias = $tokens[$endPtr]['content'];
                            $endPtr = $getTokenAs($phpcsFile, $startPtr, $endPtr);
                            yield $alias => $phpcsFile->getTokensAsString(
                                $startPtr,
                                $endPtr - $startPtr + 1
                            );
                        } else {
                            yield $tokens[$endPtr]['content'] => $phpcsFile->getTokensAsString(
                                $startPtr,
                                $endPtr - $startPtr + 1
                            );
                        }
                    })($phpcsFile, $ptr + 1, $endPtr - 1) as $alias => $cn
                ) {
                    $addImport($alias, $base . $cn);
                }
            } elseif (T_STRING === $tokens[$endPtr]['code']) {
                $alias = $tokens[$endPtr]['content'];
                $endPtr = $getTokenAs($phpcsFile, $startPtr, $endPtr);
                $addImport($alias, $phpcsFile->getTokensAsString($startPtr, $endPtr - $startPtr + 1));
            }
        }
        return $imports;
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
        $importStatements = $this->findClassImports($phpcsFile, $cStackPtr);
        foreach ($names as &$name) {
            if ($name[0] !== '\\' && isset($importStatements[$name])) {
                $name = $importStatements[$name];
            }
        }
        return $names;
    }
}
