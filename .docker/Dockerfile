#syntax=docker/dockerfile:1.6

ARG PHP_VERSION=8.2
ARG COMPOSER_VERSION=2.6

FROM composer:${COMPOSER_VERSION} AS composer_image

FROM php:${PHP_VERSION}-cli-alpine
COPY --from=composer_image --link /usr/bin/composer /usr/local/bin/composer
WORKDIR /usr/src/app/
RUN set -eux; \
    apk update \
 && apk upgrade \
 && apk add --no-cache git linux-headers \
 && apk add --update --no-cache --virtual .build-dependencies $PHPIZE_DEPS \
 && pecl install xdebug \
 && docker-php-ext-enable xdebug \
 && pecl clear-cache \
 && apk del .build-dependencies \
 && mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" \
 && git clone https://github.com/PHPCSStandards/PHP_CodeSniffer.git . \
 && composer install
