FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy app files
COPY . .

# Install dependencies
RUN composer install --optimize-autoloader --no-dev

# Setup environment
RUN cp .env.example .env && \
    php artisan config:clear && \
    php artisan key:generate && \
    php artisan config:cache

# Force migrate and clear caches (use with caution on production)
RUN php artisan migrate:fresh --force && \
    php artisan view:clear && \
    php artisan config:clear && \
    php artisan cache:clear && \
    php artisan storage:link || true

# Expose port and start app
EXPOSE 10000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
