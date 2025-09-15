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
    oniguruma-dev \
    icu-dev \
    supervisor \
    libpq \
    libpq-dev \
    && docker-php-ext-install \
    zip \
    pdo \
    pdo_pgsql \
    gd \
    mbstring \
    gmp \
    exif \
    bcmath \
    xml \
    intl \
    && rm -rf /tmp/*

# Install Composer from URL (cara yang lebih disarankan)
# Note: Ini lebih baik daripada menggunakan curl raw installer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copy your custom Nginx config file into the container
COPY ./docker/production/nginx/default.conf /etc/nginx/conf.d/default.conf

COPY ./docker/supervisor.conf /etc/supervisor/supervisord.conf

# Copy the entire application directory into the image.
RUN git config --global --add safe.directory /var/www/html

COPY . /var/www/html

# Set permissions and ownership for the application
RUN chmod -R 775 /var/www/html/storage
RUN chmod -R 775 /var/www/html/bootstrap/cache

RUN composer update

RUN npm install && npm run build

USER www-data

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf", "-n"]
