#!/bin/bash
docker run -d -p 6379:6379 redis
docker run -d -p 11211:11211 memcached
php -S 0.0.0.0:8888 -t Application/Lumen/public/

