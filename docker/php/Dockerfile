FROM composer:1.10.1 AS composer

################################################################################

FROM php:7.4-fpm-alpine AS base

RUN apk add --no-cache icu-dev \
 && docker-php-source extract \
 && docker-php-ext-install -j$(nproc) opcache intl pdo \
 && docker-php-source delete
COPY ./docker/php/crontab /etc/cron.d/crontab
RUN chmod 644 /etc/cron.d/crontab \
 && crontab /etc/cron.d/crontab
RUN adduser --disabled-password --home /app/ --gecos '' app
WORKDIR /app/

################################################################################

FROM base AS build
ARG COMPOSER_AUTH

RUN apk add --no-cache git zip
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY --chown=app:app ./composer* /app/
USER app
RUN composer global require hirak/prestissimo \
 && COMPOSER_AUTH=${COMPOSER_AUTH} composer install --no-progress --prefer-dist --no-dev \
 && rm -rf /app/.composer/

################################################################################

FROM base AS final

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY ./docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY --chown=app:app --from=build /app/ /app/
COPY --chown=app:app . /app/
COPY ./docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
