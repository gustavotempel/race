FROM php:8.2

RUN apt-get update -y && apt-get install -y libmcrypt-dev git zip unzip libpq-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sS https://get.symfony.com/cli/installer | bash && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

WORKDIR /app
COPY . /app

RUN composer install

RUN docker-php-ext-install pdo pdo_pgsql

EXPOSE 8000

ENTRYPOINT /app/entrypoint.sh
