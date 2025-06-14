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

# Copy all application files
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Copy .env and generate key
RUN cp .env.example .env && \
    php artisan config:clear && \
    php artisan key:generate || (echo "Key generate failed" && exit 1)

# Run migrations and link storage safely
RUN php artisan migrate --force || (echo "Migration failed!" && exit 1) && \
    php artisan storage:link || true

# Expose Laravel dev server
EXPOSE 10000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
