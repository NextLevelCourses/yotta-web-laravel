#======================== CLI ==========================
FROM php:8.2-cli

# Set working directory
WORKDIR /var/www

# Install Composer# ...existing code...
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    zip \
    build-essential \
    autoconf \
    pkg-config \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd pdo_mysql zip mbstring exif pcntl bcmath \
    && pecl install grpc \
    && docker-php-ext-enable grpc \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . .

# Copy .env file
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Install dependencies
# RUN composer install --no-interaction --optimize-autoloader --prefer-dist
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader --prefer-dist --no-scripts # for noscripts if build adding when error to build

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Expose port 8085 stagging server
EXPOSE 8002

# Start Laravel's built-in server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8002"]

# # Generate optimized autoload files
# RUN php artisan package:discover