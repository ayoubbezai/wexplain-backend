#!/bin/bash
set -e

# Wait for MySQL to be ready
until mysql -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" >/dev/null 2>&1; do
  echo "Waiting for MySQL..."
  sleep 2
done

echo "MySQL is up, running migrations and seeders..."

# Run migrations and seeders
php artisan migrate --force
php artisan db:seed --class=RoleSeeder --force
php artisan db:seed --class=SuperAdminSeeder --force

# Execute the default container command (PHP-FPM)
exec "$@"
