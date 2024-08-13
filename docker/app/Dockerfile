FROM php:8.3-alpine AS php

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY --from=spiralscout/roadrunner:2024 /usr/bin/rr /usr/bin/rr

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apk add --no-cache \
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
    sockets

WORKDIR /app

COPY . /app

CMD ["rr", "serve", "-c", ".rr.yml"]