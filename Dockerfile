FROM dunglas/frankenphp:php8.4-bookworm

RUN apt-get update && apt-get install -y \
    bash \
    git \
    && rm -rf /var/lib/apt/lists/*

RUN install-php-extensions \
    pcntl \
    pdo_pgsql \
    pgsql \
    redis \
    gd \
    opcache \
    intl \
    zip \
    bcmath

WORKDIR /app

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist && \
    composer dump-autoload --optimize

RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

ENV OCTANE_SERVER=frankenphp
ENV APP_ENV=production

EXPOSE 8000

CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000"]

