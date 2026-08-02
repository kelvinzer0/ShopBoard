FROM php:8.3-alpine

RUN apk add --no-cache sqlite-libs sqlite-dev oniguruma-dev libxml2-dev \
  && docker-php-ext-install pdo_sqlite mbstring xml \
  && apk del sqlite-dev oniguruma-dev libxml2-dev \
  && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

COPY . .

RUN composer config --global policy.advisories.block false \
  && composer install --no-dev --optimize-autoloader --no-interaction --no-progress --no-scripts \
  && composer run post-autoload-dump \
  && cp .env.example .env \
  && touch database/database.sqlite \
  && php artisan key:generate --force \
  && php artisan migrate --force

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
