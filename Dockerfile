FROM dunglas/frankenphp:1.3-php8.4-alpine

RUN apk add --no-cache bash

RUN install-php-extensions \
    pcntl \
    pdo_pgsql \
    pgsql \
    intl \
    zip \
    opcache \
    bcmath

WORKDIR /var/www

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

RUN composer install --no-dev --no-scripts --no-autoloader --no-interaction

COPY . .

RUN php artisan config:clear && php artisan route:clear

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

ENV AUTORUN_LARAVEL_OCTANE=1
ENV OCTANE_SERVER=frankenphp

EXPOSE 8000

CMD ["php", "artisan", "--host=0.0.0.0", "--port=8000"]

