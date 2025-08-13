#syntax=docker/dockerfile:1.16

ARG PHP_VERSION=8.2
ARG COMPOSER_VERSION=2.7
ARG CS_BRANCH=3.13.2

FROM composer:${COMPOSER_VERSION} AS composer_image

FROM php:${PHP_VERSION}-cli-alpine AS dev_image
ARG CS_BRANCH
RUN set -eu; \
    apk update \
 && apk upgrade \
 && apk add --no-cache git linux-headers \
 && apk add --update --no-cache --virtual .build-dependencies $PHPIZE_DEPS \
 && pecl install xdebug \
 && docker-php-ext-enable xdebug \
 && pecl clear-cache \
 && apk del .build-dependencies \
 && mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" \
 && rm /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini; \
    set -eux; \
    sed -i "s|\(memory_limit =\) [1-9]\+[a-zA-Z]\+|\1 1G|" "$PHP_INI_DIR/php.ini"
# xdebug code coverage requires more memory than default amount.
COPY --from=composer_image --link /usr/bin/composer /usr/local/bin/composer
WORKDIR /usr/src/cs-test/
RUN set -eu; \
    git clone https://github.com/PHPCSStandards/PHP_CodeSniffer.git --branch ${CS_BRANCH} --single-branch . \
 && composer install
WORKDIR /usr/src/app/

FROM dev_image AS test_image
# todo: copy files