#!/bin/bash

chmod 777 -R /var/www/html/storage
php /var/www/html/artisan config:cache
php /var/www/html/artisan storage:link
touch /var/www/html/health.ok

apache2ctl -D FOREGROUND
