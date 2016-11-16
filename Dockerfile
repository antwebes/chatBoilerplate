FROM php:5.6-apache

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        mysql-client \
        libpng12-dev \
        libicu-dev \
        php5-apcu \
        libxml2-dev \
        git

RUN docker-php-ext-install iconv mcrypt && \
	docker-php-ext-install pdo_mysql && \
	docker-php-ext-install gd && \
	docker-php-ext-install mbstring && \
	docker-php-ext-install mysql && \
	docker-php-ext-install gettext && \
	docker-php-ext-install exif && \
    docker-php-ext-install intl && \
    docker-php-ext-install zip && \
    docker-php-ext-install bcmath && \
    docker-php-ext-install opcache && \
    docker-php-ext-install xml && \
    docker-php-ext-install xmlreader && \
    docker-php-ext-install xmlrpc

RUN a2enmod rewrite

ADD . /var/www/html/
ADD docker/deploy_keys/ /root/.ssh/
RUN chmod 755 /var/www/html/entrypoint.sh
RUN cp /var/www/html/docker/apache/000-default.conf /etc/apache2/sites-enabled/000-default.conf
RUN cp /var/www/html/docker/php/php-prod.ini /usr/local/etc/php/php.ini
RUN chmod 400 /root/.ssh/id_rsa && \
    mkdir /var/www/html/app/config/shared
RUN cp /usr/lib/php5/20131226/apcu.so /usr/local/lib/php/extensions/no-debug-non-zts-20131226/apcu.so
RUN SYMFONY_ENV=prod php composer.phar install --prefer-dist -o --no-dev    

EXPOSE 80
CMD ["/var/www/html/entrypoint.sh"]
