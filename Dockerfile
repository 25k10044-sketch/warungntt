FROM php:8.3-apache AS php-base

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public \
    PORT=10000 \
    APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    FILESYSTEM_DISK=public \
    SESSION_SECURE_COOKIE=true

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        $PHPIZE_DEPS \
        libfreetype6 \
        libfreetype6-dev \
        libicu72 \
        libicu-dev \
        libjpeg62-turbo \
        libjpeg62-turbo-dev \
        libonig5 \
        libonig-dev \
        libpng16-16 \
        libpng-dev \
        libwebp7 \
        libwebp-dev \
        libzip4 \
        libzip-dev \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" bcmath exif gd intl mbstring opcache pdo_mysql zip \
    && apt-get purge -y --auto-remove $PHPIZE_DEPS libfreetype6-dev libicu-dev libjpeg62-turbo-dev libonig-dev libpng-dev libwebp-dev libzip-dev \
    && rm -rf /var/lib/apt/lists/* \
    && a2enmod headers rewrite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html

FROM php-base AS dependencies

WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts --no-autoloader

FROM node:22-alpine AS frontend

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY resources ./resources
COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY --from=dependencies /var/www/html/vendor/laravel/framework/src/Illuminate/Pagination/resources/views ./vendor/laravel/framework/src/Illuminate/Pagination/resources/views
RUN npm run build

FROM php-base AS runtime

WORKDIR /var/www/html
COPY . .
COPY --from=dependencies /var/www/html/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build
COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/entrypoint.sh /usr/local/bin/warung-entrypoint

RUN chmod +x /usr/local/bin/warung-entrypoint \
    && composer dump-autoload --no-dev --optimize --no-scripts \
    && php artisan package:discover --ansi \
    && mkdir -p storage/app/public storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

ENTRYPOINT ["/usr/local/bin/warung-entrypoint"]
