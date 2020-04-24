FROM php:7.3-stretch

# System dependencies
RUN apt-get update && apt-get install -yyqq git unzip libpng-dev libxml2-dev libzip-dev

# PHP dependencies
RUN docker-php-ext-install gd mysqli opcache xmlrpc zip
RUN pecl install xdebug-beta \
    && echo 'zend_extension="/usr/local/lib/php/extensions/no-debug-non-zts-20180731/xdebug.so"' > /usr/local/etc/php/conf.d/php.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html/
CMD ["/bin/bash"]
