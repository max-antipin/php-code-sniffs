# Sniffs for [PHP_CodeSniffer](https://github.com/PHPCSStandards/PHP_CodeSniffer) by Max Antipin

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
