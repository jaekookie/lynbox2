#!/bin/sh

# Optimisations du cache Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Exécution des migrations obligatoires en production
php artisan migrate --force

# Lancement de PHP-FPM en tâche de fond
php-fpm -D

# Attendre que PHP-FPM soit prêt
sleep 2

# Substituer les variables d'environnement dans la configuration Nginx
PORT=${PORT:-80}
sed -i "s/listen \${PORT:-80}/listen $PORT/" /etc/nginx/nginx.conf

# Lancement de Nginx au premier plan pour maintenir le conteneur actif
echo "Lancement de Nginx sur le port $PORT..."
exec nginx -g "daemon off;"