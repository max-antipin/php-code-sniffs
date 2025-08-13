#syntax=docker/dockerfile:1.6

ARG PHP_VERSION=8.4
ARG COMPOSER_VERSION=2.7

FROM composer:${COMPOSER_VERSION} AS composer_image

FROM php:${PHP_VERSION}-cli-alpine
RUN set -eu; \
    apk update \
 && apk upgrade \
 && apk add --no-cache git linux-headers \
 && apk add --update --no-cache --virtual .build-dependencies $PHPIZE_DEPS \
 && pecl install xdebug \
 && docker-php-ext-enable xdebug \
 && pecl clear-cache \
 && apk del .build-dependencies \
 && mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY --from=composer_image --link /usr/bin/composer /usr/local/bin/composer
WORKDIR /usr/src/app/
RUN set -eu; \
    git clone https://github.com/PHPCSStandards/PHP_CodeSniffer.git --branch 3.13.2 --single-branch . \
 && composer install
RUN set -eux; \
    sed -i "s|\(memory_limit =\) [1-9]\+[a-zA-Z]\+|\1 1G|" "$PHP_INI_DIR/php.ini"
# xdebug code coverage requires more memory than default amount.