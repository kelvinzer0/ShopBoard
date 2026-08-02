FROM node:22-alpine AS frontend

WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm ci
COPY resources/ vite.config.js tailwind.config.js postcss.config.js ./
RUN npm run build

FROM php:8.3-alpine

RUN apk add --no-cache oniguruma-dev libxml2-dev \
  && docker-php-ext-install pdo_sqlite mbstring xml \
  && apk del oniguruma-dev libxml2-dev \
  && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

COPY composer.json ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

COPY . .
COPY --from=frontend /app/public/build public/build

RUN touch database/database.sqlite \
  && php artisan key:generate --force \
  && php artisan migrate --force

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
