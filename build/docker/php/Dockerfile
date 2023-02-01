FROM php:8.1-alpine

# Install dev dependencies
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS curl-dev libtool libxml2-dev postgresql-dev sqlite-dev

# Install production dependencies
RUN apk add --no-cache bash curl g++ gcc git icu-dev icu-libs libc-dev libzip-dev \
    oniguruma-dev make postgresql-libs zlib-dev

# Install PECL and PEAR extensions
RUN yes | pecl install igbinary redis openswoole

# Install php extensions
RUN docker-php-ext-install bcmath curl exif intl mbstring pdo pdo_pgsql pdo_sqlite

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Enable Swoole
RUN touch /usr/local/etc/php/conf.d/openswoole.ini && \
    echo 'extension=openswoole.so' > /usr/local/etc/php/conf.d/openswoole.ini

# Cleanup dev dependencies
RUN apk del -f .build-deps

# Install composer
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV PATH $PATH:/root/.composer/vendor/bin
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Setup working directory
WORKDIR /var/www/app

ENV SWOOLE_RUNTIME=1

CMD ["php", "-d", "variables_order=EGPCS", "/var/www/app/public/index.php"]
