#!/bin/sh
set -eu

case "${PORT:-10000}" in
    ''|*[!0-9]*)
        echo "PORT harus berupa angka." >&2
        exit 1
        ;;
esac

sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf

mkdir -p storage/app/public storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
php artisan storage:link --force

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    php artisan migrate --force
    php artisan catalog:seed-if-empty
fi

if [ "${SEED_DEMO_DATA:-false}" = "true" ]; then
    php artisan db:seed --force
fi

exec apache2-foreground
