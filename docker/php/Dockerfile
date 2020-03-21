########################################################################################################################
# Stage: composer
########################################################################################################################

FROM composer:1.10.1 as composer

########################################################################################################################
# Stage: base
########################################################################################################################

FROM php:7.4.3-fpm-buster as base
RUN apt-get update \
 && apt-get install -y libicu-dev cron \
 && rm -rf /var/lib/apt/lists/* \
 && docker-php-ext-install -j$(nproc) intl pdo

COPY ./docker/php/crontab /etc/cron.d/crontab
RUN chmod 644 /etc/cron.d/crontab
RUN crontab /etc/cron.d/crontab

RUN adduser --disabled-password --home /app/ --gecos '' app
WORKDIR /app/

########################################################################################################################
# Stage: build
########################################################################################################################

FROM base as build
RUN apt-get update \
 && apt-get install -y libicu-dev git unzip \
 && rm -rf /var/lib/apt/lists/*
COPY --from=composer /usr/bin/composer /usr/bin/composer
USER app
COPY --chown=app:app composer.* /app/
# RUN composer global require hirak/prestissimo
RUN composer install --no-cache --no-progress --prefer-dist --no-dev --classmap-authoritative

########################################################################################################################
# Stage: production
########################################################################################################################

FROM base as production
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY ./docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY --chown=app:app --from=build /app/ /app/
COPY --chown=app:app . /app/
COPY ./docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
