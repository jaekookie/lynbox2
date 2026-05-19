#!/bin/sh

# Optimisations du cache Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Exécution des migrations obligatoires en production
php artisan migrate --force

# Lancement de PHP-FPM en tâche de fond
php-fpm -D

# Lancement de Nginx au premier plan pour maintenir le conteneur actif
echo "Lancement de Nginx..."
exec nginx -g "daemon off;"