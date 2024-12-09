FROM mirror.gcr.io/php:8.3-fpm-bullseye

RUN apt-get update && apt-get install -y libicu-dev sudo zip \
    && docker-php-ext-install intl opcache pdo pdo_mysql

ARG APP_ENV
RUN if [ "$APP_ENV" = "dev" ]; then \
    pecl install xdebug && docker-php-ext-enable xdebug; \
    fi

WORKDIR /app

RUN curl -sS https://getcomposer.org/installer -o /composer-setup.php && \
    php /composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm /composer-setup.php

COPY [".", "."]

COPY docker-entrypoint.sh /docker-entrypoint.sh
COPY wait-for-it.sh /wait-for-it.sh

RUN chmod +x /wait-for-it.sh
RUN chmod +x /docker-entrypoint.sh

ENTRYPOINT ["/docker-entrypoint.sh"]