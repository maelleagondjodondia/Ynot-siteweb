FROM php:8.2-apache

# Activer les modules nécessaires
RUN docker-php-ext-install pdo pdo_mysql \
    && a2enmod rewrite

# Copier le projet
COPY . /var/www/html/

# Donner les permissions pour SQLite et uploads
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod 664 /var/www/html/database.sqlite \
    && mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html/uploads

EXPOSE 80
