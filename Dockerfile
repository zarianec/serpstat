FROM php:7.2-cli

WORKDIR /home/parser

# Copy working files
COPY ./ /home/parser/

# Install composer
ADD https://getcomposer.org/composer.phar /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

RUN composer install

# Install nginx
RUN apt-get update && apt-get install -y nginx
COPY ./parser.nginx.conf /etc/nginx/sites-available/default

EXPOSE 80
