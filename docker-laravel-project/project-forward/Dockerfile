# Use the official PHP image with Apache as the web server
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    sudo \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Set working directory
WORKDIR /var/www/html

COPY config.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/config.sh


# Mount volume for Laravel storage directory
VOLUME /var/www/html/storage

# Copy composer.lock and composer.json
COPY composer.lock composer.json ./

# Install composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-scripts

# Copy existing application directory contents
COPY . .

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Expose port 80 and start Apache
EXPOSE 80

# Start Apache and clear cache
CMD ["./config.sh"]
