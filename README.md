# Sniffs for [PHP_CodeSniffer](https://github.com/PHPCSStandards/PHP_CodeSniffer) by Max Antipin

[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=max-antipin_php-code-sniffs&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=max-antipin_php-code-sniffs)
[![Bugs](https://sonarcloud.io/api/project_badges/measure?project=max-antipin_php-code-sniffs&metric=bugs)](https://sonarcloud.io/summary/new_code?id=max-antipin_php-code-sniffs)
[![Code Smells](https://sonarcloud.io/api/project_badges/measure?project=max-antipin_php-code-sniffs&metric=code_smells)](https://sonarcloud.io/summary/new_code?id=max-antipin_php-code-sniffs)
[![Duplicated Lines (%)](https://sonarcloud.io/api/project_badges/measure?project=max-antipin_php-code-sniffs&metric=duplicated_lines_density)](https://sonarcloud.io/summary/new_code?id=max-antipin_php-code-sniffs)
[![Lines of Code](https://sonarcloud.io/api/project_badges/measure?project=max-antipin_php-code-sniffs&metric=ncloc)](https://sonarcloud.io/summary/new_code?id=max-antipin_php-code-sniffs)

[![Reliability Rating](https://sonarcloud.io/api/project_badges/measure?project=max-antipin_php-code-sniffs&metric=reliability_rating)](https://sonarcloud.io/summary/new_code?id=max-antipin_php-code-sniffs)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=max-antipin_php-code-sniffs&metric=security_rating)](https://sonarcloud.io/summary/new_code?id=max-antipin_php-code-sniffs)
[![Technical Debt](https://sonarcloud.io/api/project_badges/measure?project=max-antipin_php-code-sniffs&metric=sqale_index)](https://sonarcloud.io/summary/new_code?id=max-antipin_php-code-sniffs)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=max-antipin_php-code-sniffs&metric=sqale_rating)](https://sonarcloud.io/summary/new_code?id=max-antipin_php-code-sniffs)
[![Vulnerabilities](https://sonarcloud.io/api/project_badges/measure?project=max-antipin_php-code-sniffs&metric=vulnerabilities)](https://sonarcloud.io/summary/new_code?id=max-antipin_php-code-sniffs)

## Development & testing

Start dev container:
```Shell
docker compose -f .docker/compose.yaml up -d
```

For the first time run:
```Shell
docker exec php-code-sniffs-dev composer install
```

Enter dev container:
```Shell
docker exec -it php-code-sniffs-dev sh
```

Run all tests (or view this file to get list of commands for testing):
```Shell
./test.sh
```

Run all tests without creating one more service:
```Shell
PHP_VERSION=8.3 docker compose -f .docker/compose.yaml run --rm --build --quiet-build -e XDEBUG_MODE=off php-dev ./test.sh
```

Run service with test coverage:
```Shell
docker compose -f .docker/compose.yaml up dev-coverage
```
The results will be stored in `var/coverage-report/`.

Below `PHP 8.4` there is a conflict between `PHP_CodeSniffer` and `nikic/php-parser` which causes fatal error: `Token T_PUBLIC_SET has ID of type string, should be int. You may be using a library with broken token emulation`. `nikic/php-parser` package is used by `PHPUnit` while calculation code coverage.

`PHP_CodeSniffer` file `src/Util/Tokens.php`, lines `183-194`:
```PHP
// Some PHP 8.4 tokens, replicated for lower versions.
if (defined('T_PUBLIC_SET') === false) {
    define('T_PUBLIC_SET', 'PHPCS_T_PUBLIC_SET');
}

if (defined('T_PROTECTED_SET') === false) {
    define('T_PROTECTED_SET', 'PHPCS_T_PROTECTED_SET');
}

if (defined('T_PRIVATE_SET') === false) {
    define('T_PRIVATE_SET', 'PHPCS_T_PRIVATE_SET');
}
```

`nikic/php-parser` file `lib/PhpParser/compatibility_tokens.php`, lines `34-42`:
```PHP
        foreach ($compatTokens as $token) {
            if (\defined($token)) {
                $tokenId = \constant($token);
                if (!\is_int($tokenId)) {
                    throw new \Error(sprintf(
                        'Token %s has ID of type %s, should be int. ' .
                        'You may be using a library with broken token emulation',
                        $token, \gettype($tokenId)
                    ));
                }
                // ...
            }
        }
```

Run test containers with all PHP versions and code coverage:
```Shell
docker compose -f .docker/compose-test.yaml up --quiet-build
```
