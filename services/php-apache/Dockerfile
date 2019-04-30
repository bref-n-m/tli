FROM php:7.3-apache

ADD php-apache.conf /etc/apache2/sites-available/000-default.conf

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN a2enmod rewrite

# Unblock php installation
RUN rm /etc/apt/preferences.d/no-debian-php

# Add php extensions
RUN apt-get update && apt-get install -y \
    php-dev \
    php-pear \
    libyaml-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
	&& docker-php-ext-install -j$(nproc) gd pdo pdo_mysql


RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

# Install YAML extension
RUN pecl install yamL \
    && echo "extension=yaml.so" >> /usr/local/etc/php/php.ini

