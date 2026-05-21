FROM php:8.4-apache

# 1. Désactiver mpm_event AVANT d'activer mpm_prefork
RUN a2dismod mpm_event

# 2. Activer mpm_prefork
RUN a2enmod mpm_prefork

# 3. Installer les extensions PHP nécessaires pour Laravel et MySQL
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# 4. Activer le module de réécriture d'Apache (indispensable pour les routes Laravel)
RUN a2enmod rewrite

# 5. Changer la racine du serveur web Apache vers le dossier /public de Laravel
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 6. Copier les fichiers du projet
WORKDIR /var/www/html
COPY . .

# 7. Installer Composer et les dépendances du projet
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 8. Donner les bons droits d'accès aux dossiers de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Exposer le port par défaut d'Apache
EXPOSE 80

CMD ["apache2-foreground"]
