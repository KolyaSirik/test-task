#!/bin/sh
set -e

# Set correct permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Run migrations if enabled
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running migrations..."
    php artisan migrate --force
fi

# Clear and cache config
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Supervisor (which starts Nginx, FPM, Worker and Scheduler)
echo "Starting Supervisor..."
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
