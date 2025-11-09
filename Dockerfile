# ===== 1) Composer stage (PHP + composer + bcmath + zip + git) =====
FROM php:8.3-cli AS vendor
ENV COMPOSER_ALLOW_SUPERUSER=1

# APTのキャッシュを保持しない
RUN echo 'APT::Keep-Downloaded-Packages "false";' > /etc/apt/apt.conf.d/keep-cache

# OSパッケージ & PHP拡張
RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends git unzip libzip-dev; \
    rm -rf /var/lib/apt/lists/* /var/cache/apt/archives/*; \
    docker-php-ext-install bcmath zip
# composer コマンド
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /app
# まだソース全体は入れない（scriptsでartisanを呼ばせないため）
COPY composer.json composer.lock ./
# ← artisanが無い段階なので --no-scripts で実行
RUN composer install --no-dev --no-progress --prefer-dist --no-interaction --no-scripts
# ここで全ファイルをコピー
COPY . .
# 依存は揃っているので最適化のみ（scriptsは走らせない）
RUN composer dump-autoload --classmap-authoritative --no-interaction

# ===== 2) Frontend build (Vite) =====
FROM node:20 AS frontend
WORKDIR /app

# devDeps (vite等) を入れるために production 固定はしない
# ENV NODE_ENV=production にはしない（ここ重要）

# Rollup のネイティブバイナリを使わず JS/wasm にフォールバック
ENV ROLLUP_SKIP_NODEJS_NATIVE=1

# 先に lock と package 情報だけ
COPY package.json package-lock.json ./

# devDeps を含めてクリーンインストール
RUN set -eux; \
  npm ci --include=dev || (rm -rf node_modules package-lock.json && npm install --include=dev); \
  npm cache clean --force

# 残りのソースを入れてビルド
COPY . .
RUN npm run build && npm cache clean --force


# ===== 3) Runtime (php-fpm) =====
FROM php:8.3-fpm
# 本番でも必要な拡張を入れる

# 追加: APTのキャッシュを保持しない
RUN echo 'APT::Keep-Downloaded-Packages "false";' > /etc/apt/apt.conf.d/keep-cache

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends libzip-dev; \
    rm -rf /var/lib/apt/lists/* /var/cache/apt/archives/*; \
    docker-php-ext-install pdo_mysql opcache bcmath zip

WORKDIR /var/www/html
COPY --from=vendor /app /var/www/html
COPY --from=frontend /app/public/build /var/www/html/public/build
# 推奨: 権限とOPcacheの基本設定
RUN chown -R www-data:www-data storage bootstrap/cache \
 && { \
      echo 'opcache.enable=1'; \
      echo 'opcache.validate_timestamps=0'; \
      echo 'opcache.jit_buffer_size=64M'; \
      echo 'opcache.memory_consumption=192'; \
    } > /usr/local/etc/php/conf.d/opcache.ini
EXPOSE 9000
