########################################################################################################################
# Stage: composer
########################################################################################################################

FROM composer:1.10.1 as composer

########################################################################################################################
# Stage: base
########################################################################################################################

FROM php:7.4.3-fpm-buster as base
RUN apt-get update \
 && apt-get install -y libicu-dev git unzip \
 && docker-php-ext-install -j$(nproc) intl pdo
RUN adduser --disabled-password --gecos '' app
RUN mkdir /app/
RUN chown -R app: /app/
WORKDIR /app/

########################################################################################################################
# Stage: build
########################################################################################################################

FROM base as build
COPY --from=composer /usr/bin/composer /usr/bin/composer
USER app
COPY --chown=app:app composer.* /app/
RUN composer install --no-cache --no-progress --prefer-dist --no-dev --classmap-authoritative

########################################################################################################################
# Stage: production
########################################################################################################################

FROM base as production
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY ./docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY --from=build /app/ /app/
COPY --chown=app:app . /app/
CMD ["php-fpm"]
