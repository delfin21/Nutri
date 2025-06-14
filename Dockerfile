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

# Set working dir
WORKDIR /var/www

# Copy app files
COPY . .

# Install PHP packages
RUN composer install --optimize-autoloader --no-dev

# Create default .env
RUN cp .env.example .env

# Generate Laravel key
RUN php artisan key:generate

# Run migrations and storage link
RUN php artisan migrate --force && php artisan storage:link

# Expose port and serve
EXPOSE 10000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
