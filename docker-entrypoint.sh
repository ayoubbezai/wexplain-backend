#!/bin/sh
set -e

# Wait for MySQL
echo "Waiting for MySQL at $DB_HOST:$DB_PORT..."
until php -r "new PDO('mysql:host=$DB_HOST;dbname=$DB_DATABASE', '$DB_USERNAME', '$DB_PASSWORD');" >/dev/null 2>&1; do
  echo "MySQL not ready yet..."
  sleep 3
done

echo "MySQL is up! Running migrations and seeders..."
php artisan migrate --force --seed || true

echo "Starting PHP-FPM..."
exec php-fpm
