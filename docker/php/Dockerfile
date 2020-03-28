FROM composer:1.10.1 AS composer

################################################################################

FROM php:7.4-fpm-alpine AS base

RUN apk add --no-cache icu-dev \
 && docker-php-source extract \
 && docker-php-ext-install -j$(nproc) opcache intl pdo \
 && docker-php-source delete

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

FROM base AS php

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY ./docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
USER app
COPY --chown=app:app --from=build /app/ /app/
COPY --chown=app:app . /app/

################################################################################

FROM php AS cron

USER root
COPY ./docker/php/crontab /etc/cron.d/crontab
RUN chmod 644 /etc/cron.d/crontab

ENV SUPERCRONIC_URL=https://github.com/aptible/supercronic/releases/download/v0.1.9/supercronic-linux-amd64 \
    SUPERCRONIC=supercronic-linux-amd64 \
    SUPERCRONIC_SHA1SUM=5ddf8ea26b56d4a7ff6faecdd8966610d5cb9d85

RUN curl -fsSLO "$SUPERCRONIC_URL" \
 && echo "${SUPERCRONIC_SHA1SUM}  ${SUPERCRONIC}" | sha1sum -c - \
 && chmod +x "$SUPERCRONIC" \
 && mv "$SUPERCRONIC" "/usr/local/bin/${SUPERCRONIC}" \
 && ln -s "/usr/local/bin/${SUPERCRONIC}" /usr/local/bin/supercronic

USER app
CMD ["supercronic", "-json", "/etc/cron.d/crontab"]
