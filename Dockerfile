#Installiere Alpine, PHP und Nginx
FROM serversideup/php:8.3-fpm-nginx-alpine
USER root

# Workdirectory setzen
WORKDIR /var/www/html

# Ldap installieren
RUN apk add --update openldap
# Ldap certificate prüfen auf never stellen
RUN echo "TLS_REQCERT never" >> /etc/openldap/ldap.conf

# Install the intl extension with root permissions
RUN install-php-extensions ldap imagick gd

# Projekt kopieren
COPY . /var/www/html

# .env.example kopieren
COPY .env.example.productive /var/www/html/.env

# Laravel.log file erstellen
RUN touch /var/www/html/storage/logs/laravel.log
# Laravel.log file rechte zu www-data geben
RUN chown -R www-data:www-data /var/www/html/storage/logs/laravel.log
# Workdirectory rechte zu www-data geben
RUN chown -R www-data:www-data /var/www/html
# Composer und npm installieren
RUN composer install --no-dev --optimize-autoloader

# NPM Tasks
RUN apk add --update npm
RUN npm i
RUN npm run build

# Laravel App Key generieren
RUN php artisan key:generate

# Imagick Limits erhöhen
RUN sed -ri -e 's/<policy domain="resource" name="memory" value="256MiB"\/>/<policy domain="resource" name="memory" value="2GiB"\/>/g' /etc/ImageMagick-6/policy.xml
RUN sed -ri -e 's/<policy domain="resource" name="map" value="512MiB"\/>/<policy domain="resource" name="map" value="8GiB"\/>/g' /etc/ImageMagick-6/policy.xml
RUN sed -ri -e 's/<policy domain="resource" name="disk" value="1GiB"\/>/<policy domain="resource" name="disk" value="8GiB"\/>/g' /etc/ImageMagick-6/policy.xml

# User auf www-data setzen
USER www-data
