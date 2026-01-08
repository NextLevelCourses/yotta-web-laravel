FROM php:8.2-cli

WORKDIR /var/www

# Install OS packages and PHP extensions (curl included for health checks)
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libzip-dev libpng-dev libonig-dev libxml2-dev \
    libfreetype6-dev libjpeg62-turbo-dev libwebp-dev \
    build-essential autoconf pkg-config \
  && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
  && docker-php-ext-install gd pdo_mysql zip mbstring exif pcntl bcmath \
  && pecl install grpc \
  && docker-php-ext-enable grpc \
  && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install PHP dependencies (cache-friendly: copy lock files first)
COPY composer.json composer.lock ./
RUN COMPOSER_MEMORY_LIMIT=-1 composer install \
  --no-dev --prefer-dist --no-interaction --no-progress \
  --no-scripts

# Copy application source code
COPY . .

# Optimize autoload and run package discovery (tolerant if build-time env is incomplete)
RUN composer dump-autoload -o --no-interaction \
  && php artisan package:discover --no-interaction || true

# Prepare Laravel runtime directories and permissions
RUN mkdir -p storage bootstrap/cache \
  && chown -R www-data:www-data /var/www \
  && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 8002

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8002"]
