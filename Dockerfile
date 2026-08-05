# Stage 1: Build frontend assets
FROM node:22-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY resources/ vite.config.js tailwind.config.js postcss.config.js ./
COPY resources/css/app.css resources/css/
COPY resources/js/ resources/js/
RUN npm run build

# Stage 2: PHP application
FROM php:8.3-alpine

RUN apk add --no-cache sqlite-libs sqlite-dev oniguruma-dev libxml2-dev \
  && docker-php-ext-install pdo_sqlite mbstring xml \
  && apk del sqlite-dev oniguruma-dev libxml2-dev \
  && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

COPY composer.json ./
RUN composer config --global policy.advisories.block false \
  && composer install --no-dev --optimize-autoloader --no-interaction --no-progress --no-scripts

COPY . .
COPY --from=frontend /app/public/build public/build

RUN composer dump-autoload -o \
  && composer run post-autoload-dump \
  && cp .env.example .env \
  && touch database/database.sqlite \
  && mkdir -p storage/framework/{sessions,cache,views} storage/logs \
  && php artisan key:generate --force \
  && php artisan migrate --force

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
