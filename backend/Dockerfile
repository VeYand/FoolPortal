FROM mirror.gcr.io/php:8.2.2-fpm-bullseye

RUN apt-get update && apt-get install -y git libicu-dev sudo zip \
    && docker-php-ext-install intl opcache pdo pdo_mysql

WORKDIR /app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | sudo -E bash
RUN sudo apt install symfony-cli

COPY [".", "."]

COPY docker-entrypoint.sh /docker-entrypoint.sh

RUN chmod +x /docker-entrypoint.sh
ENTRYPOINT ["/docker-entrypoint.sh"]