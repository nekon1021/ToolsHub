# ===== 1) PHP deps =====
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-progress --prefer-dist --classmap-authoritative
COPY . .
RUN composer dump-autoload --classmap-authoritative

# ===== 2) Frontend build (Vite) =====
FROM node:20 AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# ===== 3) Runtime =====
FROM php:8.3-fpm
RUN docker-php-ext-install pdo_mysql opcache
WORKDIR /var/www/html
COPY --from=vendor /app /var/www/html
COPY --from=frontend /app/public/build /var/www/html/public/build
RUN chown -R www-data:www-data storage bootstrap/cache
EXPOSE 9000
