# Sniffs for [PHP_CodeSniffer](https://github.com/PHPCSStandards/PHP_CodeSniffer) by Max Antipin

## Development & testing
```Shell
docker compose -f .docker/compose.yaml up -d
```

```Shell
php ./vendor/bin/phpcs --ignore=vendor/,var/,*.inc --standard=AntipinCS ./AntipinCS/

php ./vendor/bin/phpcs --ignore=vendor/,var/ --standard=AntipinCS ./AntipinCS/Tests/

php ./vendor/bin/phpcs --ignore=vendor/,var/ --standard=AntipinCS/testset.xml --sniffs=AntipinCS.Functions.FunctionDeclarationArgumentType ./AntipinCS/Tests/
```

```Shell
php ./vendor/bin/phpunit --filter AntipinCS --no-coverage
```