#!/bin/sh

echo "Set the base directory for the app"
BASEDIR=/var/www/html

echo "Check if the .env file exists"
if [ ! -f "$BASEDIR/.env" ]; then
   # abort the script if the .env file does not exist
    echo "The .env file does not exist. Please create it."
    exit 1
fi

echo "Clear the old boostrap/cache"
php artisan clear-compiled

# Clear the cache
php artisan cache:clear
php artisan event:clear
php artisan config:clear
php artisan route:clear

echo "Generate application key"
php artisan key:generate --force

echo "Remove prior storage links that exist"
rm -rf public/storage

echo "Build up a new storage link"
php artisan storage:link

echo "Set the permissions for the storage directories"
# chown -R www-data:www-data "$BASEDIR"
# chmod -R 755 "$BASEDIR/storage"
chmod -R a+w "$BASEDIR/storage"

echo "run migrations"
php artisan migrate --force

echo "seed database"
php artisan db:seed --force

echo "Starting supervisord"
exec /usr/bin/supervisord -c /etc/supervisord.conf
