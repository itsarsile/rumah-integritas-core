# Stage 1: Dependencies
FROM composer:2 AS vendor

WORKDIR /app

# Copy only composer files for better caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-scripts

FROM node:20-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# Stage 2: Application
FROM php:8.4-fpm-alpine

# Install system dependencies & PHP extensions
RUN apk add --no-cache \
    bash \
    curl \
    git \
    icu-dev \
    libpq-dev \        
    libzip-dev \
    oniguruma-dev \
    supervisor \
    nginx \
    zip \
    unzip \
    linux-headers \
    && docker-php-ext-install \
    bcmath \
    intl \
    pcntl \
    posix \
    pdo_mysql \
    pdo_pgsql \        
    opcache \
    sockets \
    zip

WORKDIR /var/www/html

# Copy application source
COPY . .

# Copy vendor from builder stage
COPY --from=vendor /app/vendor ./vendor

# Make Composer available in final image (needed for dump-autoload)
COPY --from=vendor /usr/bin/composer /usr/bin/composer

# Fix permissions for Laravel storage & cache
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Generate optimized autoloader only (avoid caching config at build time)
RUN composer dump-autoload --optimize

# Copy Supervisor & Nginx configs
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/nginx.conf /etc/nginx/nginx.conf

EXPOSE 80
EXPOSE 8080

# Use entrypoint to run runtime optimizations, then start Supervisor
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
