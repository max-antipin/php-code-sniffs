# Sniffs for [PHP_CodeSniffer](https://github.com/PHPCSStandards/PHP_CodeSniffer) by Max Antipin

## Development & testing
```Shell
docker compose -f .docker/compose.yaml up -d
```

```Shell
php ./vendor/bin/phpcs --ignore=vendor/,var/,*.inc --standard=PSR12Ext ./PSR12Ext/

php ./vendor/bin/phpcs --ignore=vendor/,var/ --standard=PSR12Ext ./PSR12Ext/Tests/
```

```Shell
php ./vendor/bin/phpunit --filter PSR12Ext --no-coverage
```