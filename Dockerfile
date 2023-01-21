FROM php:8.0-apache
RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
RUN install-php-extensions xdebug