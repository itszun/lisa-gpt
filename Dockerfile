# Gunakan image PHP berbasis Debian (Bullseye) yang lebih stabil.
FROM php:8.3-fpm-bullseye

# Update package list dan install dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
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

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) \
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
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-enable gd

# Set working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copy Supervisor config dan Nginx config
COPY ./docker/supervisor.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./docker/production/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy application files
RUN git config --global --add safe.directory /var/www/html
COPY . /var/www/html

# Update Composer dan npm dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN npm install && npm run build

# Set permissions
RUN chmod -R 775 storage bootstrap/cache

# Ganti user ke www-data
USER www-data

# Jalankan Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf", "-n"]
