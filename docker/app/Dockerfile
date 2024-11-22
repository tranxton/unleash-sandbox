FROM php:8.3-alpine AS php

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY --from=spiralscout/roadrunner:2024 /usr/bin/rr /usr/bin/rr

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apk update && apk upgrade && apk add --no-cache \
    postgresql-dev \
    oniguruma-dev \
    linux-headers \
    libxml2-dev \
    libpq-dev \
    autoconf \
	g++ \
	make \
    git \
    zip \
    && docker-php-ext-install \
    pdo_pgsql \
    mbstring \
    session \
    simplexml \
    opcache \
    sockets \
    && pecl install  \
    redis \
    && docker-php-ext-enable  \
    redis \
    opcache

WORKDIR /app

COPY ./docker/app/opcache.ini /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini
COPY .                        /app

RUN git config --global --add safe.directory /app

CMD ["rr", "serve", "-c", ".rr.yaml"]