FROM php:8.2-fpm

RUN apt-get update \
  && apt-get install -y \
             libcurl4-openssl-dev \
             pkg-config \
             libpq-dev\
  && docker-php-ext-install pgsql \
  && docker-php-ext-install pdo_pgsql \
  && docker-php-source delete

WORKDIR /srv

CMD ["php-fpm"]