FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libssl-dev pkg-config unzip \
    && docker-php-ext-install pdo_mysql \
    && pecl install mongodb redis \
    && docker-php-ext-enable mongodb redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY . /var/www/html/

EXPOSE 80
