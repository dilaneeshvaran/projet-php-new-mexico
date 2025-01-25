ARG PHP_VERSION=8.4.3

FROM php:$PHP_VERSION-fpm

ARG PHP_USER_NAME=php
ARG PHP_USER_IDENTIFIER=1000
ARG PHP_USER_SHELL=/bin/bash
ARG PHP_USER_HOME_DIRECTORY=/var/www

RUN useradd --create-home --system --home-dir $PHP_USER_HOME_DIRECTORY --shell $PHP_USER_SHELL --uid $PHP_USER_IDENTIFIER $PHP_USER_NAME

RUN docker-php-ext-install pdo_mysql

# parse yaml file in index (used to read public/routes.yml)
RUN apt-get update && \
    apt-get install -y libyaml-dev && \
    pecl install yaml && \
    docker-php-ext-enable yaml