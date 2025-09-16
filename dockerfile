#================= Gunakan PHP 8.2 + Apache ================
# FROM php:8.2-apache

# # Set working directory
# WORKDIR /var/www/html

# # Install system dependencies
# RUN apt-get update && apt-get install -y \
#     git \
#     unzip \
#     curl \
#     libpq-dev \
#     libzip-dev \
#     libonig-dev \
#     libxml2-dev \
#     pkg-config \
#     libssl-dev \
#     && docker-php-ext-install pdo pdo_mysql zip mbstring bcmath pcntl xml

# # Install gRPC extension
# RUN pecl install grpc \
#     && docker-php-ext-enable grpc

# # Aktifkan mod_rewrite
# RUN a2enmod rewrite

# # Copy Composer dari official image
# COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# # Copy project ke dalam container
# COPY . .

# # Pastikan .env ada
# RUN if [ ! -f .env ]; then cp .env.example .env; fi

# # Install PHP dependencies
# RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader

# # Set permission untuk Laravel
# RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
#     && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# # Expose port 80
# EXPOSE 80

# # Start Apache
# CMD ["apache2-foreground"]

#======================== CLI ==========================
# Use the official PHP image with Apache
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
    zip \
    && pecl install grpc \
    && docker-php-ext-enable grpc \
    && docker-php-ext-install pdo_mysql zip mbstring exif pcntl bcmath gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

# Expose port 8085 stagging server
EXPOSE 8000

# Start Laravel's built-in server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
