#!/bin/bash
set -e

# Wait for database if needed (optional but good)
# while ! mysqladmin ping -h"$DB_HOST" --silent; do
#     sleep 1
# done

# Cache Laravel configuration
echo "Caching Laravel configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations if flag is set
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running migrations..."
    php artisan migrate --force
fi

# Set permissions for storage & cache at runtime just in case
chown -R www-data:www-data storage bootstrap/cache

# Execute the original command (Apache)
exec "$@"
