# Stage 1: Build PHP dependencies
FROM composer:2.7 as vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-reqs --no-scripts

# Stage 2: Build frontend assets
FROM php:8.3-cli-alpine as frontend
WORKDIR /app
RUN apk add --no-cache nodejs npm
COPY package*.json ./
RUN npm install
COPY . .
COPY --from=vendor /app/vendor/ /app/vendor/
RUN cp .env.example .env && php artisan key:generate
RUN npm run build

# Stage 3: Production image
FROM php:8.4-fpm-alpine

# Install system dependencies, Nginx, and Supervisor
RUN apk add --no-cache \
    nginx \
    supervisor \
    postgresql-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    oniguruma-dev \
    icu-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring zip exif pcntl bcmath gd intl

# Set working directory
WORKDIR /var/www/html

# Copy vendor from the vendor stage
COPY --from=vendor /app/vendor/ /var/www/html/vendor/

# Copy frontend assets from the frontend stage
COPY --from=frontend /app/public/build/ /var/www/html/public/build/

# Copy application files
COPY . /var/www/html/

# Copy Nginx and Supervisor configurations
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy and setup entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose port 80
EXPOSE 80

# Run entrypoint script
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
