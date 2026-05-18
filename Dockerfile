# --- Étape 1 : Build des dépendances PHP avec Composer ---
FROM php:8.2-fpm-alpine AS backend-builder

# Installer TOUTES les dépendances système nécessaires à la compilation
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
    build-base

# Installer et activer les extensions PHP nécessaires pour Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Récupérer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Installer les dépendances PHP sans les scripts de dev
RUN composer install --no-dev --optimize-autoloader --no-scripts