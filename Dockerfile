# Use official PHP image with FPM
FROM php:8.2-fpm

# Install system dependencies
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

# Copy all application files
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Clear and cache configuration
RUN php artisan config:clear && php artisan config:cache

# Copy .env and generate app key
RUN cp .env.example .env && \
    php artisan key:generate || (echo "Key generate failed" && exit 1)

# Run database migrations (optional: you can remove --force for safety)
RUN php artisan migrate --force || (echo "Migration failed!" && exit 1)

# Create storage symlink
RUN php artisan storage:link || true

# Expose port and start Laravel development server
EXPOSE 10000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
