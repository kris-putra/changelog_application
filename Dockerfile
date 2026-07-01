FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev zip nodejs npm

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . /var/www/html

RUN composer install --no-dev --prefer-dist --no-interaction --no-scripts || true
RUN npm install || true
RUN npm run build || true

CMD ["php-fpm"]
