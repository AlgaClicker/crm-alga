#!/bin/bash
php artisan doctrine:clear:metadata:cache

rm Application/Lumen/storage/tmp-entities -fr
mkdir -p Application/Lumen/storage/tmp-entities
php artisan doctrine:generate:entities /storage/tmp-entities/ --generate-annotations --generate-methods --regenerate-entities
chmod 777 Application/Lumen/storage/tmp-entities -R

cp ./Application/Lumen/storage/tmp-entities/Domain/Entities/Business/ Domain/Entities/ -fr
cp ./Application/Lumen/storage/tmp-entities/Domain/Entities/Services/ Domain/Entities/ -fr
cp ./Application/Lumen/storage/tmp-entities/Domain/Entities/News/ Domain/Entities/ -fr
cp ./Application/Lumen/storage/tmp-entities/Domain/Entities/Utils/ Domain/Entities/ -fr

chmod 777 Domain/Entities/Services -R
chmod 777 Domain/Entities/Business -R
chmod 777 Domain/Entities/News -R
chmod 777 Domain/Entities/Utils -R

# rm Application/Lumen/storage/tmp-entities -fr
