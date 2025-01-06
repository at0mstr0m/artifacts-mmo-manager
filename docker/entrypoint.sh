#!/bin/sh

# Set the base directory for the app
BASEDIR=/var/www/html

# Check if the .env file exists
if [ ! -f "$BASEDIR/.env" ]; then
   # abort the script if the .env file does not exist
    echo "The .env file does not exist. Please create it."
    exit 1
fi

# Clear the old boostrap/cache
php artisan clear-compiled

# Clear the optimization cache
php artisan optimize:clear

# Generate application key
php artisan key:generate --force

# Remove prior storage links that exist
rm -rf public/storage

# Build up a new storage link
php artisan storage:link

# Set the permissions for the storage directories
chown -R www-data:www-data "$BASEDIR"
chmod -R 755 "$BASEDIR/storage"

php artisan migrate --force

# Check if running in production or development mode
if [ "$PRODUCTION" = "1" ]; then
    echo "Running in production mode"
else
    echo "Running in development mode"
fi

# Start supervisord
exec /usr/bin/supervisord -c /etc/supervisord.conf
