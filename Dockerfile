FROM php:8.1-fpm

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
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the environment variable to allow Composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Set working directory
WORKDIR /var/www/html

# Copy composer.json and composer.lock first to leverage Docker cache
COPY composer.json composer.lock ./


# Copy existing application directory contents
COPY . /var/www/html

# Install Composer dependencies
# RUN composer install --no-interaction --no-plugins --no-scripts --no-dev --prefer-dist

# Copy the rest of the application code
COPY . .

# Copy .env file if it's not copied during COPY . .
RUN cp .env.example .env

# Set file permissions
RUN chown -R www-data:www-data /var/www/html

# Install Node dependencies and build frontend assets
RUN npm install
RUN npm run production

# Expose port 8000 and start php-fpm server
EXPOSE 8000
CMD ["php-fpm"]
