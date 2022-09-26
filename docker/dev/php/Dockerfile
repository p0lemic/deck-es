FROM php:8.1-apache

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y libpq-dev git zip unzip libicu-dev \
    && docker-php-source extract \
    && docker-php-source delete \
    && docker-php-ext-install -j$(nproc) pdo_pgsql intl bcmath \
    && rm -rf /tmp/*

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

COPY docker/dev/php/my-config.ini /usr/local/etc/php/conf.d/my-config.ini

ENV APACHE_DOCUMENT_ROOT /var/www/deck/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/deck
