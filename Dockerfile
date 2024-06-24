FROM php:8.3.6-cli-alpine3.19 AS os
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer/composer:2-bin /composer /usr/bin/composer
WORKDIR /var/www
