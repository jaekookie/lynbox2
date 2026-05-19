# --- Étape 1 : Build des dépendances PHP avec Composer ---
FROM php:8.2-fpm-alpine AS backend-builder

# Installer les dépendances système (Ajout de zlib-dev ici)
RUN apk add --no-cache \
    bash \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libxml2-dev \
    zip \
    libzip-dev \
    unzip \
    git \
    oniguruma-dev \
    zlib-dev \
    build-base

# Installer et activer les extensions PHP nécessaires pour Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Récupérer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Installer les dépendances PHP de production
RUN composer install --no-dev --optimize-autoloader --no-scripts

# --- Étape 2 : Environnement d'exécution final ---
FROM php:8.2-fpm-alpine

# Installer Nginx et les dépendances d'exécution (Ajout de zlib ici aussi)
RUN apk add --no-cache nginx bash libpng libjpeg-turbo freetype libzip oniguruma zlib

# Réinstaller les extensions PHP dans le conteneur final
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath zip

WORKDIR /var/www/html

# Copier l'application construite
COPY --from=backend-builder /app /var/www/html

# Définir les droits d'accès pour Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copier la configuration de Nginx
COPY nginx.conf /etc/nginx/nginx.conf

# Rendre le script d'entrée exécutable
RUN chmod +x /var/www/html/entrypoint.sh

# Ouvrir le port 80
EXPOSE 80

# Lancer le conteneur via le script d'entrée
CMD ["/var/www/html/entrypoint.sh"]