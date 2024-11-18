#!/bin/sh


php artisan cache:clear
php artisan doctrine:clear:metadata:cache
php artisan doctrine:clear:query:cache
php artisan doctrine:clear:result:cache
php artisan doctrine:schema:update --force
php artisan doctrine:generate:proxies
composer dump
chmod 777 Infrastructure/Doctrine/Proxies/ -R
