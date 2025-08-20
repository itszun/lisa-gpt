# --- STAGE 1: PRODUCTION BUILD ---
FROM php:8.3-fpm-alpine AS production

# Install PHP extensions, including common ones for Laravel.
RUN apk add --no-cache \
    nginx \
    libzip-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    git \
    bash \
    oniguruma-dev && \
    docker-php-ext-install pdo_mysql opcache bcmath exif gd pcntl zip && \
    # Clean up to keep the image small
    rm -rf /var/cache/apk/*

# Copy the Laravel application.
COPY --chown=www-data:www-data . /var/www/html

# --- STAGE 2: DEVELOPMENT BUILD ---
# This stage adds developer tools on top of the production image.
FROM php:8.3-fpm AS development

# Use ARGs for environment variables passed from the Docker build command.
ARG XDEBUG_ENABLED=true
ARG XDEBUG_MODE=develop,coverage,debug,profile
ARG XDEBUG_HOST=host.docker.internal
ARG XDEBUG_IDE_KEY=VSCODE
ARG XDEBUG_LOG=/dev/stdout
ARG XDEBUG_LOG_LEVEL=0

USER root

# Install necessary development tools for a robust build
RUN apt-get update && apt-get install -y \
    git \
    nodejs \
    npm \
    libzip-dev \
    libjpeg-dev \
    libpng-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configure Xdebug if enabled.
RUN if [ "${XDEBUG_ENABLED}" = "true" ]; then \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    echo "xdebug.mode=${XDEBUG_MODE}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.idekey=${XDEBUG_IDE_KEY}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_host=${XDEBUG_HOST}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
fi

# Set permissions and ownership for the application.
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Switch back to the non-privileged user.
USER www-data

# Set the working directory to the Laravel app.
WORKDIR /var/www/html

# Define default command for PHP-FPM.
CMD ["php-fpm"]
