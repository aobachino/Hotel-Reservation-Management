FROM php:8.1-fpm

WORKDIR /var/www/html

# Copy the rest of the application code
COPY . .

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

RUN cp .env.example .env

ENV COMPOSER_HOME=/var/www/html/vendor

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy composer.json and composer.lock
COPY composer.json composer.lock ./

# Install Laravel dependencies
RUN composer install --no-scripts --no-autoloader

# RUN chmod +x ./docker-entrypoint.sh
# Install Node dependencies and build frontend assets
RUN npm install
RUN npm run production

# Expose port 8000 and start php-fpm server
EXPOSE 8000
CMD ["php-fpm"]
#CMD ["php", "artisan", "serve", "--host", "0.0.0.0", "--port", "8000"]
