#!/bin/bash

if [ "$ENV" = "dev" ]
then
    echo "executing php in dev mode"
    cp /var/www/html/docker/php/php-dev.ini /usr/local/etc/php/php.ini
    chown -R www-data:www-data /var/www/html/app/cache/

    apache2-foreground
else
    echo "executing php in PROD mode"
    cp /var/www/html/docker/php/php-prod.ini /usr/local/etc/php/php.ini
    rm /var/www/html/app/config/parameters.yml
    ln -s /var/www/html/app/config/shared/parameters.yml /var/www/html/app/config/parameters.yml
    php app/console cache:clear -e=prod --no-warmup
    php app/console assets:install -e=prod
    php app/console  assetic:dump -e=prod
    chown -R www-data:www-data /var/www/html/app/cache/ /var/www/html/app/logs/ /var/www/html/web/media

    apache2-foreground
fi


