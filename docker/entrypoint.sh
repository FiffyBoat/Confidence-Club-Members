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

# Railway (and similar platforms) expect the app to listen on $PORT.
if [ -n "$PORT" ]; then
  sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
  sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf
fi

# Avoid noisy Apache warnings
if ! grep -q "^ServerName" /etc/apache2/apache2.conf; then
  echo "ServerName localhost" >> /etc/apache2/apache2.conf
fi

exec apache2-foreground
