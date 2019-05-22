FROM composer:1.8.5 as composer



FROM php:7.3.5-cli-stretch as base

RUN apt-get update \
 && apt-get install -y libicu-dev git unzip cron \
 && docker-php-ext-install -j$(nproc) intl pdo pdo_mysql

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

RUN apt-get update \
 && apt-get install -y wait-for-it \
 && rm -rf /var/lib/apt/lists/*

COPY --from=build /app/ /app/

COPY . /app/

COPY ./docker/crawler/crontab /etc/cron.d/crontab

COPY ./docker/crawler/start.sh /start.sh

RUN crontab -u app /etc/cron.d/crontab

CMD ["/start.sh"]
