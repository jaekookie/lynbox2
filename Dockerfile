FROM php:8.4-apache

# 1. Installer les extensions PHP nécessaires pour Laravel et MySQL
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# 2. Activer le module de réécriture d'Apache (indispensable pour les routes Laravel)
RUN a2enmod rewrite

# 3. Changer la racine du serveur web Apache vers le dossier /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Copier les fichiers du projet
WORKDIR /var/www/html
COPY . .

# 5. Installer Composer et les dépendances du projet
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 6. Donner les bons droits d'accès aux dossiers de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 7. Exposer le port par défaut d'Apache
EXPOSE 80

CMD ["apache2-foreground"]