FROM php:8.1-apache
RUN apt-get update
RUN apt-get install -y git libzip-dev zip unzip npm vim
RUN docker-php-ext-install pdo pdo_mysql zip mysqli exif
COPY local.event.conf /etc/apache2/sites-available/local.event.conf
COPY files /var/www/html/
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html/
RUN a2enmod rewrite
RUN a2ensite local.event.conf