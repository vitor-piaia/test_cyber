FROM dunglas/frankenphp:php8.4-bookworm

RUN apt-get update && apt-get install -y \
    bash \
    unzip \
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

RUN chown -R www-data:www-data \
    /app/storage \
    /app/bootstrap/cache

EXPOSE 8000

CMD ["frankenphp", "run", "--config", "/app/Caddyfile"]
