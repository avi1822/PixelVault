#!/bin/bash
set -e

# Dynamically update Nginx port if Render provides a $PORT environment variable
PORT="${PORT:-8080}"
sed -i "s/listen 8080/listen ${PORT}/g" /etc/nginx/nginx.conf
sed -i "s/listen \[::\]:8080/listen \[::\]:${PORT}/g" /etc/nginx/nginx.conf

# Parse DATABASE_URL if provided by Render PostgreSQL
if [ -n "$DATABASE_URL" ]; then
    export DB_CONNECTION=pgsql
    # Regex extraction of database credentials from postgresql://user:pass@host:port/dbname
    PROTO="$(echo $DATABASE_URL | grep :// | sed -e's,^\(.*://\).*,\1,g')"
    URL="$(echo ${DATABASE_URL/$PROTO/})"
    USERPASS="$(echo $URL | grep @ | cut -d@ -f1)"
    export DB_USERNAME="$(echo $USERPASS | grep : | cut -d: -f1)"
    export DB_PASSWORD="$(echo $USERPASS | grep : | cut -d: -f2)"
    HOSTPORT="${URL/$USERPASS@/}"
    HOSTPATH="$(echo $HOSTPORT | cut -d/ -f1)"
    export DB_HOST="$(echo $HOSTPATH | grep : | cut -d: -f1)"
    export DB_PORT="$(echo $HOSTPATH | grep : | cut -d: -f2)"
    if [ -z "$DB_PORT" ]; then export DB_PORT="5432"; fi
    export DB_DATABASE="$(echo $URL | grep / | cut -d/ -f2- | cut -d? -f1)"
    export DB_SSLMODE="require"
fi

# Run storage symlink
php artisan storage:link || true

# Run cache & optimizations
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Safely run migrations if DB is accessible
if [ -n "$DB_HOST" ]; then
    php artisan migrate --force || true
fi

# Start supervisord to launch PHP-FPM & Nginx
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
