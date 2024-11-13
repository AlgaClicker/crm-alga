FROM node:15-alpine

### Install Laravel Echo Server and dependencies
RUN apk add --update --no-cache openssl curl \
 && apk add --update --no-cache --virtual .build-deps \
        binutils-gold \
        g++ \
        gcc \
        gnupg \
        libgcc \
        linux-headers \
        make \
        python \
 && yarn global add --prod --no-lockfile laravel-echo-server \
 && apk del .build-deps \
 && yarn cache clean

COPY . /usr/local/
RUN mkdir -p /project/src
RUN pwd
COPY ./src/laravel-echo-server.json /project/src/laravel-echo-server.json


RUN chmod 777 /usr/local/bin/docker-entrypoint.sh

WORKDIR /project

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
#ENTRYPOINT ["/bin/sh"]


EXPOSE 6001

HEALTHCHECK --interval=30s --timeout=5s \
        CMD /usr/local/bin/health-check

CMD ["start", "--force"]