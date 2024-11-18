FROM nginx
ADD provision/docker-images/nginx/nginx.conf /etc/nginx/conf.d/default.conf

RUN echo "Asia/Yekaterinburg" > /etc/timezone

RUN echo "upstream php-upstream { server api:9000; }" > /etc/nginx/conf.d/upstream.conf

RUN usermod -u 1000 www-data

WORKDIR /etc/nginx

EXPOSE 80
EXPOSE 443
