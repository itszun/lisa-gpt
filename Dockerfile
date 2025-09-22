# Pake image PHP terbaru dengan FPM dan alpine.
FROM php:8.3-fpm-alpine

# Install dependency sistem yang dibutuhkan oleh PHP dan Laravel.
RUN apk add --no-cache \
    nginx \
    postgresql-dev \
    supervisor \
    icu-dev \
    libzip-dev \
    && docker-php-ext-install \
    pdo_pgsql \
    pdo \
    intl \
    zip \
    && rm -rf /var/cache/apk/*

# Gunakan image composer untuk menginstal dependensi (Multi-Stage Build)
COPY --from=composer/composer:latest /usr/bin/composer /usr/local/bin/composer

# Pindah ke workdir aplikasi.
WORKDIR /var/www/html


# Salin semua file dari folder aplikasi lokal ke dalam container.
COPY . .

RUN composer install --optimize-autoloader

# Konfigurasi dan setup lainnya seperti sebelumnya
COPY ./docker/nginx/nginx.conf /etc/nginx/conf.d/default.conf
COPY ./docker/supervisord.conf /etc/supervisor/supervisord.conf

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
