# Multi-stage Dockerfile for PixelVault PS5 Gaming Lounge (Laravel 9 + Node/Vite)

# Stage 1: Build Frontend Assets with Node
FROM node:20-alpine AS frontend-builder

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources/ ./resources/
COPY public/ ./public/
COPY vite.config.js ./
RUN npm run build

# Stage 2: Production PHP-FPM + Nginx Environment
FROM php:8.2-fpm-alpine

# Install system packages & PHP extensions (pdo_pgsql, pdo_sqlite, etc.)
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    pkgconfig \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    postgresql-dev \
    sqlite-dev \
    oniguruma-dev \
    zip \
    unzip \
    bash

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo pdo_pgsql pdo_sqlite gd zip mbstring bcmath opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application files
COPY . .

# Copy compiled Vite assets from Stage 1
COPY --from=frontend-builder /app/public/build ./public/build

# Install PHP production dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Configure storage permissions
RUN mkdir -p storage/framework/sessions \
             storage/framework/views \
             storage/framework/cache \
             bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy Nginx & Supervisor configurations
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
