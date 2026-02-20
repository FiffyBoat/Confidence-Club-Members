#!/usr/bin/env sh
set -e

php artisan package:discover --ansi || true
php artisan storage:link || true
php artisan migrate --force || true

if [ -z "$APP_KEY" ]; then
  echo "APP_KEY is not set. Generate one and set it in Render."
else
  php artisan optimize || true
fi

exec apache2-foreground
