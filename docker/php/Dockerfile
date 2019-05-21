########################################################################################################################
# Stage: composer
########################################################################################################################

FROM composer:1.8.5 as composer

########################################################################################################################
# Stage: base
########################################################################################################################

FROM php:7.3.5-fpm-stretch as base

RUN apt-get update \
 && apt-get install -y libicu-dev git unzip \
 && docker-php-ext-install -j$(nproc) intl pdo pdo_mysql

RUN mkdir /app/

RUN adduser --disabled-password --gecos '' app

RUN chown -R app: /app/

WORKDIR /app/

########################################################################################################################
# Stage: build
########################################################################################################################

FROM base as build

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY composer* /app/

USER app

RUN composer global require hirak/prestissimo

RUN composer install

########################################################################################################################
# Stage: tools
########################################################################################################################

FROM base as tools

RUN mkdir ~/.gnupg

RUN echo "disable-ipv6" >> ~/.gnupg/dirmngr.conf

RUN apt-get update \
 && apt-get install -y wget gpg sudo \
 && rm -rf /var/lib/apt/lists/*

RUN wget -O phive.phar https://phar.io/releases/phive.phar \
 && wget -O phive.phar.asc https://phar.io/releases/phive.phar.asc \
 && gpg --keyserver pool.sks-keyservers.net --recv-keys 0x9D8A98B29B2D5D79 \
 && gpg --verify phive.phar.asc phive.phar \
 && rm phive.phar.asc \
 && chmod +x phive.phar \
 && sudo mv phive.phar /usr/local/bin/phive

COPY phive.xml /app/

RUN phive --no-progress install --copy --force-accept-unsigned --trust-gpg-keys 4AA394086372C20A,C5095986493B4AA0,AFA6EAAB339B841E,31C7E470E2138192

########################################################################################################################
# Stage: migrations
########################################################################################################################

FROM tools as migrations

RUN apt-get update \
 && apt-get install -y wait-for-it \
 && rm -rf /var/lib/apt/lists/*

COPY --from=build /app/ /app/

COPY . /app/

COPY docker/php/migrate.sh /app/migrate.sh

CMD ["/app/migrate.sh"]

########################################################################################################################
# Stage: production
########################################################################################################################

FROM base as production

COPY --from=build /app/ /app/

COPY . /app/
