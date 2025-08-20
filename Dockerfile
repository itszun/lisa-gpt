# Use a lightweight Debian-based image for consistency and ease of use.
FROM php:8.3-fpm-alpine

# Set the working directory
WORKDIR /var/www/html

# Install required dependencies
RUN apk add --no-cache \
    git \
    nodejs \
    npm \
    libzip-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libxml2-dev \
    gmp-dev \
    && docker-php-ext-install \
    zip \
    pdo_mysql \
    gd \
    mbstring \
    gmp \
    exif \
    bcmath \
    xml \
    && rm -rf /tmp/*

# Install Composer from URL (cara yang lebih disarankan)
# Note: Ini lebih baik daripada menggunakan curl raw installer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copy your custom Nginx config file into the container
COPY ./docker/production/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy the entire application directory into the image.
COPY . /var/www/html

# Set permissions and ownership for the application
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 775 /var/www/html/storage
RUN chmod -R 775 /var/www/html/bootstrap/cache

# Switch to the non-privileged user
RUN git config --global --add safe.directory /var/www/html
USER www-data

# Define default command for PHP-FPM
CMD ["php-fpm"]
