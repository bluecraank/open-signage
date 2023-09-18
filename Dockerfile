FROM php:8.2-apache

LABEL maintainer "blueCraanK"
ARG NODE_VERSION=20

RUN apt-get update && \
    apt-get install -y \
    libfreetype6-dev \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    ghostscript \
    git

# Install Node.js & NPM
RUN curl -fsSL https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - &&\
    apt-get install -y nodejs
RUN npm install -g npm

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer self-update

# Install PHP extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo_mysql zip bcmath ldap imagick

# Clone source code if not downloaded
RUN rm -rf /var/www/html
RUN git clone https://github.com/bluecraank/open-signage.git /var/www/html
COPY .env.example /var/www/html/.env

# Apache configuration
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN touch /var/www/html/storage/logs/laravel.log
RUN chown -R www-data:www-data /var/www/html/storage/logs/laravel.log
RUN chown -R www-data:www-data /var/www/html/

# Install dependencies
WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# Artisan commands
USER www-data
WORKDIR /var/www/html
RUN php artisan config:cache
RUN php artisan config:clear
RUN php artisan cache:clear
RUN php artisan key:generate

# LDAP without certificate
USER root
RUN echo "TLS_REQCERT never" >> /etc/ldap/ldap.conf

# Allow .htaccess
RUN sed -ri -e 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Set max_upload_size
RUN echo "php_value upload_max_filesize 100M" >> /var/www/html/public/.htaccess
RUN echo "php_value post_max_size 100M" >> /var/www/html/public/.htaccess

# Allow pdf imaging
RUN sed -ri -e 's/<policy domain="coder" rights="none" pattern="PDF" \/>/<policy domain="coder" rights="read|write" pattern="PDF" \/>/g' /etc/ImageMagick-6/policy.xml

USER root
RUN chown -R www-data:www-data /var/www/html/
