#Installiere Alpine, PHP und Nginx
FROM serversideup/php:8.3-fpm-nginx-alpine
USER root

# Workdirectory setzen
WORKDIR /var/www/html

# Projekt kopieren
COPY . /var/www/html

# .env.example kopieren
COPY .env.example.productive /var/www/html/.env

# Packages installieren
RUN apk add --no-cache --virtual build-depend --update libjpeg-turbo-dev libpng-dev libzip-dev ghostscript openldap npm && \
    echo "TLS_REQCERT never" >> /etc/openldap/ldap.conf && \
    install-php-extensions ldap imagick gd && \
    touch /var/www/html/storage/logs/laravel.log && \
    chown -R www-data:www-data /var/www/html/storage/logs/laravel.log && \
    chown -R www-data:www-data /var/www/html && \
    composer install --no-dev --optimize-autoloader && \
    npm i && \
    npm run build && \
    php artisan key:generate && \
    sed -ri -e 's/<policy domain="resource" name="memory" value="256MiB"\/>/<policy domain="resource" name="memory" value="2GiB"\/>/g' /etc/ImageMagick-7/policy.xml && \
    sed -ri -e 's/<policy domain="resource" name="map" value="512MiB"\/>/<policy domain="resource" name="map" value="8GiB"\/>/g' /etc/ImageMagick-7/policy.xml && \
    sed -ri -e 's/<policy domain="resource" name="disk" value="1GiB"\/>/<policy domain="resource" name="disk" value="8GiB"\/>/g' /etc/ImageMagick-7/policy.xml && \
    apk del build-depend

# User auf www-data setzen
USER www-data
