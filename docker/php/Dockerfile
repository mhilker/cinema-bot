FROM composer:1.8.5 as composer



FROM php:7.3.5-fpm-stretch as base

RUN apt-get update \
 && apt-get install -y libicu-dev git unzip \
 && docker-php-ext-configure intl \
 && docker-php-ext-install -j$(nproc) intl

RUN mkdir /app/

RUN adduser --disabled-password --gecos '' app

RUN chown -R app: /app/

WORKDIR /app



FROM base as build

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY composer* /app/

USER app

RUN composer global require hirak/prestissimo

RUN composer install




FROM base as production

COPY --from=build /app/ /app/

COPY . /app/
