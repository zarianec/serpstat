FROM php:7.2-cli

WORKDIR /home/parser

COPY ./ /home/parser/

# Install composer
ADD https://getcomposer.org/composer.phar /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

RUN composer install
