# Multi-stage Dockerfile for Laravel 12 + Reverb (production)

# 1) Composer dependencies
FROM composer:2 AS composer_deps
WORKDIR /app

# Leverage Docker layer cache for Composer
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-progress \
    --no-interaction \
    --no-scripts \
    --optimize-autoloader

# 2) Node build (Vite)
FROM node:20-alpine AS frontend_build
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --omit=dev=false
COPY . .
# Build assets if Vite config exists; tolerate missing front-end
RUN mkdir -p public/build && if [ -f ./vite.config.js ]; then npm run build; fi

# 3) PHP runtime base with required extensions (Debian for reliable builds)
FROM php:8.3-fpm-bookworm AS runtime-base
WORKDIR /var/www/html

# Install system deps and PHP extensions commonly required by Laravel + Reverb
RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
      bash git curl unzip ca-certificates \
      libicu-dev libzip-dev zlib1g-dev \
      libpng-dev libjpeg62-turbo-dev libfreetype6-dev libwebp-dev \
      libpq-dev \
    ; \
    rm -rf /var/lib/apt/lists/*; \
    apt-get update; \
    apt-get install -y --no-install-recommends $PHPIZE_DEPS; \
    docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp; \
    docker-php-ext-install -j$(nproc) \
      bcmath \
      gd \
      intl \
      mbstring \
      opcache \
      pcntl \
      pdo \
      pdo_mysql \
      pdo_pgsql \
      sockets \
      zip; \
    pecl install redis; \
    docker-php-ext-enable redis; \
    apt-get purge -y --auto-remove $PHPIZE_DEPS; \
    rm -rf /var/lib/apt/lists/*

# Configure PHP for production (opcache)
COPY --chown=www-data:www-data . .

# Copy vendor and built assets from builders
COPY --from=composer_deps /app/vendor ./vendor
COPY --from=frontend_build /app/public/build ./public/build

# Ensure storage/cache/logs are writable
RUN set -eux; \
    mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache; \
    chown -R www-data:www-data storage bootstrap/cache; \
    find storage -type d -exec chmod 775 {} \; ; \
    find storage -type f -exec chmod 664 {} \;

# Opcache recommended settings
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.enable_cli=1'; \
    echo 'opcache.jit=1255'; \
    echo 'opcache.jit_buffer_size=128M'; \
    echo 'opcache.memory_consumption=256'; \
    echo 'opcache.max_accelerated_files=20000'; \
  } > /usr/local/etc/php/conf.d/opcache.ini

# Small healthcheck script
HEALTHCHECK --interval=30s --timeout=5s --start-period=20s CMD php -v || exit 1

# Add entrypoint for cache warmups and storage link
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Default environment knobs
ENV APP_ENV=production \
    PHP_FPM_LISTEN=9000 \
    REVERB_HOST=0.0.0.0 \
    REVERB_PORT=8080

# Drop to non-root user for runtime
USER www-data

# 4a) PHP-FPM target (use with a web server like Nginx/Caddy)
FROM runtime-base AS app-fpm
EXPOSE 9000
ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm", "-F"]

# 4b) Reverb WebSocket server target
# Runs Laravel Reverb on the configured host/port.
FROM runtime-base AS app-reverb
EXPOSE 8080
ENTRYPOINT ["/entrypoint.sh"]
CMD ["sh", "-lc", "php artisan reverb:start --host=${REVERB_HOST:-0.0.0.0} --port=${REVERB_PORT:-8080}"]

# 4c) Optional: queue worker target
FROM runtime-base AS app-queue
ENTRYPOINT ["/entrypoint.sh"]
CMD ["sh", "-lc", "php artisan queue:work --sleep=1 --tries=3 --verbose"]
