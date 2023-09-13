FROM php:8.2-apache

LABEL maintainer "Nils Fischer"
ARG NODE_VERSION=20

RUN apt-get update && \
    apt-get install -y \
    libfreetype6-dev \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    ghostscript

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - &&\
    apt-get install -y nodejs

RUN npm install cross-env

RUN npm install -g yarn
RUN npm install -g npm

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer self-update

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Look at this links to see which extensions are supported: https://github.com/mlocati/docker-php-extension-installer#supported-php-extensions
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions zip zip bcmath ldap

RUN a2enmod rewrite

RUN install-php-extensions imagick

COPY . /var/www/html

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

RUN chown -R www-data:www-data /var/www/html/storage
RUN touch /var/www/html/storage/logs/laravel.log
RUN chown -R www-data:www-data /var/www/html/storage/logs/laravel.log
RUN touch /var/www/html/database/database.sqlite
RUN chown -R www-data:www-data /var/www/html/database/database.sqlite
RUN /var/www/html/artisan migrate

RUN echo "TLS_REQCERT never" >> /etc/ldap/ldap.conf

COPY .docker.env /var/www/html/.env
