FROM php:8.2-fpm


RUN apt-get update && \
    apt-get install -y --no-install-recommends libssl-dev zlib1g-dev curl git unzip mc nano netcat-traditional libxml2-dev libpq-dev libzip-dev && \
    apt-get install -y nodejs npm \
      libmagickwand-dev --no-install-recommends && \
    pecl install apcu && \
    docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install -j$(nproc) zip opcache intl pdo_pgsql pgsql bcmath && \
    docker-php-ext-enable apcu pdo_pgsql sodium

RUN apt-get update && apt-get install -y libc-client-dev libkrb5-dev && rm -r /var/lib/apt/
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl && docker-php-ext-install imap

RUN apt-get update && \
    apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev libwebp-dev

RUN docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype
RUN docker-php-ext-install gd && \
    docker-php-ext-enable gd

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    npm install --global yarn

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer && chmod +x /usr/bin/composer

ARG DOCKER_PHP_ENABLE_XDEBUG='on'

# Enable Xdebug
RUN if [ "${DOCKER_PHP_ENABLE_XDEBUG}" = "on" ]; then \
      pecl install xdebug && \
      docker-php-ext-enable xdebug; \
      echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini && \
      echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini && \
      echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini && \
      echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/xdebug.ini && \
      php -m; \
    else \
      echo "Skip xdebug support"; \
    fi

COPY php.ini /usr/local/etc/php

WORKDIR /var/www

EXPOSE 9000
