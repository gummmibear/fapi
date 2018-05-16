FROM php:7.2-fpm

RUN apt-get update

RUN apt-get install -y zlib1g-dev libpq-dev git libicu-dev libxml2-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install zip xml


#RUN pecl install mongodb \
#    && echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/ext-mongodb.ini
RUN curl --insecure https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer

# Set timezone
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Poland /etc/localtime
RUN "date"

RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

WORKDIR /var/www/symfony