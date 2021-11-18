FROM php:8.1.0RC5-apache

RUN docker-php-ext-install pdo pdo_mysql sockets
RUN a2enmod rewrite

# install programs/packages needed for composer updates/installs
RUN apt-get -y update
RUN apt-get -y install git zip unzip

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

COPY . /var/www
WORKDIR /var/www
RUN composer update
RUN composer install

COPY ./.env.example /var/www/.env
COPY ./public /var/www/html

RUN chown -R www-data:www-data /var/www/