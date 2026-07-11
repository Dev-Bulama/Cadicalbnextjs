##############################################################################
# Cadical Solutions (Laravel) — Multi-stage Dockerfile
##############################################################################

# ── Stage 1: Composer dependencies ──────────────────────────────────────────
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev --no-interaction --no-progress --no-scripts --prefer-dist \
    --optimize-autoloader

# ── Stage 2: Runtime image ──────────────────────────────────────────────────
FROM php:8.3-fpm-alpine AS runner
WORKDIR /var/www/html

RUN apk add --no-cache \
        icu-dev libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev oniguruma-dev \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install pdo_mysql intl zip gd bcmath opcache

RUN addgroup -g 1000 laravel && adduser -G laravel -g laravel -s /bin/sh -D laravel

COPY --from=vendor /app/vendor ./vendor
COPY . .

RUN chown -R laravel:laravel /var/www/html \
    && chmod -R 775 storage bootstrap/cache

USER laravel

RUN php artisan package:discover --ansi

EXPOSE 9000
CMD ["php-fpm"]
