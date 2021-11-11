FROM php:8.1.0RC5-apache

RUN docker-php-ext-install pdo pdo_mysql sockets
RUN a2enmod rewrite


COPY . /var/www
COPY ./public /var/www/html
RUN chown -R www-data:www-data /var/www/