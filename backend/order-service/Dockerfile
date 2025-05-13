# Gunakan image PHP + FPM
FROM php:8.2-fpm

# Install dependencies sistem
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set direktori kerja
WORKDIR /var/www

# Copy source code
COPY . .

# Install dependency Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Set permission Laravel
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

# Expose port agar dapat diakses
EXPOSE 9000

# Jalankan Laravel menggunakan built-in server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=9000"]
