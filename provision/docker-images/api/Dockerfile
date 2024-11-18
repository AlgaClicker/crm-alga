FROM php:8.2.3-fpm
ARG BUILDPLATFORM=linux/amd64
ARG TARGETPLATFORM=linux/arm64

ENV MAX_FILE_SIZE 200M

ENV TZ APP_TIMEZONE
RUN echo "Asia/Yekaterinburg" > /etc/timezone

RUN apt-get update && apt-get install -y libssl-dev libcurl4-openssl-dev libicu-dev libpcre3-dev libzip-dev libonig-dev libpq-dev unzip git libmcrypt-dev zlib1g-dev supervisor
RUN apt-get install -y libpq-dev libmemcached-dev curl cron procps libsodium-dev wget alien

RUN docker-php-ext-install pdo pdo_pgsql bcmath pcntl
RUN apt-get install -y libmemcached-dev

RUN docker-php-ext-enable bcmath
RUN yes "no" | pecl install memcached redis
RUN docker-php-ext-enable memcached redis

RUN apt-get install tzdata
RUN ln -snf /usr/share/zoneinfo/${APP_TIMEZONE} /etc/localtime && echo ${APP_TIMEZONE} > /etc/timezone

COPY provision/docker-images/api/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY provision/docker-images/api/php-api.ini /usr/local/etc/php/conf.d/php-api.ini

COPY provision/docker-images/api/cron.conf /etc/cron.d/cron
COPY ./provision/docker-images/api/entrypoint.sh /etc/entrypoint.sh
RUN chmod +x /etc/entrypoint.sh

RUN ls -lah /etc/entrypoint.sh

RUN chmod 0644 /etc/cron.d/cron
RUN touch /var/log/cron.log

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD backend /var/www/html

WORKDIR /var/www/html

RUN rm Application/Lumen/storage/tmp-entities -fr

RUN composer install

RUN usermod -u 1000 www-data
RUN usermod -s /usr/sbin/nologin www-data

RUN mkdir -p Application/Lumen/storage
RUN mkdir -p Application/Lumen/storage/framework/{sessions,cache,views}
RUN mkdir -p Application/Lumen/storage/logs
RUN touch Application/Lumen/storage/logs/worker.log

RUN chown www-data:www-data Application/Lumen/storage -R
RUN chmod +rx Application/Lumen -R
RUN chmod +rw Application/Lumen/storage -R



EXPOSE 9000
ENTRYPOINT ["/bin/sh","/etc/entrypoint.sh"]

