#!/bin/bash
set -e

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run Laravel optimizations (best-effort)
if [ "$APP_ENV" = "production" ]; then
    echo "Running production optimizations..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
    php artisan event:cache || true
fi

# Create storage link if it doesn't exist
if [ ! -L public/storage ]; then
    php artisan storage:link || true
fi

# Optionally run migrations and seeds at startup
if [ "${AUTO_MIGRATE:-0}" = "1" ]; then
    echo "Running database migrations..."
    ATTEMPTS=10
    until php artisan migrate --force --no-interaction; do
        ATTEMPTS=$((ATTEMPTS-1))
        if [ $ATTEMPTS -le 0 ]; then
            echo "Migrations failed after retries; continuing startup."
            break
        fi
        echo "Migration failed; retrying in 3s... ($ATTEMPTS left)"
        sleep 3
    done

    if [ "${AUTO_SEED:-0}" = "1" ]; then
        if [ -n "${SEED_CLASS:-}" ]; then
            echo "Seeding database with class ${SEED_CLASS}..."
            php artisan db:seed --class="${SEED_CLASS}" --force || true
        else
            echo "Seeding database (DatabaseSeeder)..."
            php artisan db:seed --force || true
        fi
    fi
fi

# Execute the main command
exec "$@"
