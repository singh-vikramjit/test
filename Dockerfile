FROM php:7.1-apache
COPY . /var/www/html/
WORKDIR "/var/www/html/"

# Install git
RUN apt-get update \
    && apt-get -y install git \
    && apt-get -y install unzip \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN docker-php-ext-install pdo_mysql

RUN ls -al

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer
RUN composer install -n --prefer-dist

RUN ls -al

RUN chmod -R 0777 tl/tmp
RUN a2enmod rewrite

#RUN pecl install stackdriver_debugger-alpha