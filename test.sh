#!/bin/sh
set -eu

cd ../cs-test/
php ./vendor/bin/phpunit --filter AntipinCS

cd ../app/
php ./vendor/bin/phpcs
php ./vendor/bin/phpstan analyze
php ./vendor/bin/phpcs-check-feature-completeness