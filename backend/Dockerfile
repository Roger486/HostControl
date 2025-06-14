# Use official PHP 8.2 image with FPM
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy existing application directory
COPY . .

# Install Laravel dependencies
RUN composer install --optimize-autoloader --no-dev

# Copy permissions fix script (optional if needed later)
# RUN chown -R www-data:www-data /var/www

# Expose port (if serving with php -S in dev, otherwise managed by nginx)
EXPOSE 8000

CMD ["php-fpm"]
