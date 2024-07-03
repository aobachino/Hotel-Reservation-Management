FROM php:8.1-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update && \
    apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libcurl4-openssl-dev \
    libonig-dev \
    nodejs \
    npm

RUN docker-php-ext-install -j$(nproc) zip
RUN docker-php-ext-install -j$(nproc) pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the rest of the application code
COPY . .

# Copy composer.json and composer.lock first to leverage Docker cache
COPY composer.json composer.lock ./

# Install PHP dependencies
# RUN composer install --no-interaction

# Copy .env file if it's not copied during COPY . .
RUN cp .env.example .env

# Set file permissions
RUN chown -R www-data:www-data /var/www/html

# Install npm dependencies and the Laravel Vite plugin
# RUN npm install
# RUN npm install laravel-vite-plugin --save-dev

# Run build
# RUN npm run build

# Generate application key
# RUN php artisan key:generate

# Expose port 8000 and start php-fpm server
EXPOSE 8000
CMD ["php-fpm"]
