set -eu
composer validate
php ./vendor/bin/phpcs-check-feature-completeness
php ./vendor/bin/phpcs
php ./vendor/bin/phpstan analyze