FROM php:8.4-apache

# 1. Désactiver mpm_event et nettoyer complètement
RUN a2dismod mpm_event && \
    rm -f /etc/apache2/mods-enabled/mpm_event.* && \
    rm -f /etc/apache2/mods-available/mpm_event.*

# 2. Activer mpm_prefork
RUN a2enmod mpm_prefork

# 3. Installer les extensions PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# 4. Activer rewrite
RUN a2enmod rewrite

# 5. Configurer Apache pour Laravel
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 6. Copier le projet
WORKDIR /var/www/html
COPY . .

# 7. Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 8. Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]