FROM php:7.4.8-apache

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y libpq-dev git zip unzip \
    && docker-php-source extract \
    && pecl install xdebug redis \
    && docker-php-ext-enable xdebug \
    && docker-php-source delete \
    && docker-php-ext-install pdo_pgsql bcmath sockets \
    && rm -rf /tmp/*

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

RUN curl --silent --location https://deb.nodesource.com/setup_12.x | bash
RUN apt-get install --yes nodejs
RUN npm install -g gulp
RUN apt-get install --yes build-essential

COPY docker/dev/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY docker/dev/php/my-config.ini /usr/local/etc/php/conf.d/my-config.ini

ENV APACHE_DOCUMENT_ROOT /var/www/deck/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
