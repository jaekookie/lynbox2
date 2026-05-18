# --- Étape 1 : Build des dépendances PHP avec Composer ---
FROM php:8.2-fpm-alpine AS backend-builder

# Installer les dépendances système requises pour les extensions PHP
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
    oniguruma-dev

# Installer et activer les extensions PHP nécessaires pour Laravel (dont PDO MySQL)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Récupérer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Installer les dépendances PHP sans les scripts de dev
RUN composer install --no-dev --optimize-autoloader --no-scripts

# --- Étape 2 : Environnement d'exécution final ---
FROM php:8.2-fpm-alpine

RUN apk add --no-cache nginx caddy bash supervisor libpng libjpeg-turbo freetype libzip oniguruma

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath zip

WORKDIR /var/www/html

# Copier les fichiers depuis le builder
COPY --from=backend-builder /app /var/www/html

# Configurer les permissions indispensables pour Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copier la configuration Nginx (définie à l'étape suivante)
COPY nginx.conf /etc/nginx/nginx.conf

# Exposer le port par défaut que Railway va attribuer
EXPOSE 80

# Script de démarrage pour exécuter les migrations et lancer le serveur
CMD sh -c "php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan migrate --force && nginx -g 'daemon off;' & php-fpm"