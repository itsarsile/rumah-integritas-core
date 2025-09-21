#!/usr/bin/env sh
set -e

# Simple, idempotent runtime setup for Laravel in containers

echo "[entrypoint] Running Laravel runtime setup..."

# Ensure the APP_KEY exists; do not auto-generate in prod
if [ -z "$APP_KEY" ]; then
  echo "[entrypoint] Warning: APP_KEY is not set. Some Artisan caches may fail." >&2
fi

# Storage symlink (safe to re-run)
if [ ! -L public/storage ]; then
  php artisan storage:link || true
fi

# Cache warmups (do not fail hard to ease first boot)
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true
php artisan route:clear || true

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true
php artisan event:cache || true

echo "[entrypoint] Setup complete. Starting: $*"
exec "$@"

