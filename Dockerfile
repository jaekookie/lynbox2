FROM php:8.4-fpm-alpine

# 1. Installer les extensions PHP et Nginx sur une base Alpine légère
RUN apk add --no-cache \
    nginx \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip \
    git \
    bash \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# 2. Configurer Nginx pour Laravel
RUN mkdir -p /run/nginx
RUN echo ' \
server { \
    listen 80; \
    root /var/www/html/public; \
    index index.php index.html; \
    charset utf-8; \
    location / { \
        try_files $uri $uri/ /index.php?$query_string; \
    } \
    location ~ \.php$ { \
        fastcgi_pass 127.0.0.1:9000; \
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; \
        include fastcgi_params; \
    } \
}' > /etc/nginx/http.d/default.conf

# 3. Mettre en place le dossier de travail
WORKDIR /var/www/html
COPY . .

# 4. Installer Composer et les dépendances
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 5. Droits d'accès pour Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 6. Exposer le port 80
EXPOSE 80

# 7. Lancer PHP-FPM et Nginx en même temps
CMD php-fpm -D && nginx -g "daemon off;"