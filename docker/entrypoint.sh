#!/bin/bash

if [ "$ENV" = "dev" ]
then
    echo "executing php in dev mode"
    cp /docker/php/php-dev.ini /usr/local/etc/php/php.ini
    chown -R www-data:www-data /var/www/html/app/cache/
    rm /etc/apache2/sites-enabled/000-default-ssl.conf
    apache2-foreground
else
    echo "executing php in PROD mode"
    cp /docker/php/php-prod.ini /usr/local/etc/php/php.ini
    rm /var/www/html/app/config/parameters.yml
    ln -s /var/www/html/app/config/shared/parameters.yml /var/www/html/app/config/parameters.yml
    php app/console cache:clear -e=prod --no-warmup
    php app/console assets:install -e=prod
    php app/console  assetic:dump -e=prod
    mkdir -p /var/www/html/web/media/image/
    chown -R www-data:www-data /var/www/html/app/cache/ /var/www/html/app/logs/ /var/www/html/web/media

    RUN SYMFONY_ENV=prod php composer.phar install --prefer-dist -o --no-dev

    apache2-foreground
fi


