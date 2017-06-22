FROM php:7.0-apache

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        mysql-client \
        libpng12-dev \
        libicu-dev \
        git \
        nano

RUN docker-php-ext-install iconv mcrypt && \
    docker-php-ext-install pdo_mysql && \
    docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
    docker-php-ext-install -j$(nproc) gd && \
    docker-php-ext-install mbstring && \
    docker-php-ext-install gettext && \
    docker-php-ext-install exif && \
    docker-php-ext-install intl && \
    docker-php-ext-install zip && \
    docker-php-ext-install opcache



RUN a2enmod rewrite && \
    a2enmod headers  && \
    a2enmod expires && \
    a2enmod ssl

ADD . /var/www/html/
ADD docker/entrypoint.sh /entrypoint.sh
RUN chmod 755 /entrypoint.sh

ADD docker/apache/000-default.conf /etc/apache2/sites-enabled/000-default.conf
ADD docker/apache/mod-pagespeed-stable_current_amd64.deb docker/apache/mod-pagespeed-stable_current_amd64.deb

RUN dpkg -i docker/apache/mod-pagespeed-stable_current_amd64.deb

ADD . /var/www/html/
ADD docker/php/php-prod.ini /usr/local/etc/php/php.ini


ADD docker/apache/pagespeed.conf /etc/apache2/mods-enabled/pagespeed.conf


EXPOSE 80 443
CMD ["/entrypoint.sh"]
