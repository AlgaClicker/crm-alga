#!/bin/sh
set -x
rm /project/laravel-echo-server.json -fr

# laravel-echo-server init
if [[ "$1" == 'init' ]]; then
    set -- laravel-echo-server "$@"
fi

# laravel-echo-server <sub-command>
if [[ "$1" == 'start' ]] || [[ "$1" == 'client:add' ]] || [[ "$1" == 'client:remove' ]]; then
    if [[ "${GENERATE_CONFIG:-true}" == "false" ]]; then
        # wait for another process to inject the config
        echo -n "Waiting for /project/laravel-echo-server.json"
        while [[ ! -f /project/laravel-echo-server.json ]]; do
            sleep 2
            echo -n "."
        done
    elif [[ ! -f /project/laravel-echo-server.json ]]; then
        cp /project/src/laravel-echo-server.json /project/laravel-echo-server.json
        # Replace with environment variables
        sed -i "s|LARAVEL_ECHO_SERVER_DB|${LARAVEL_ECHO_SERVER_DB:-redis}|i" /project/laravel-echo-server.json
        sed -i "s|REDIS_HOST|${REDIS_HOST:-redis}|i" /project/laravel-echo-server.json
        sed -i "s|REDIS_PORT|${REDIS_PORT:-6379}|i" /project/laravel-echo-server.json
        sed -i "s|REDIS_DATABASE_SOCKET|${REDIS_DATABASE_SOCKET:-5}|i" /project/laravel-echo-server.json

    fi
    set -- laravel-echo-server "$@"
fi

# first arg is `-f` or `--some-option`
if [[ "${1#-}" != "$1" ]]; then
	set -- laravel-echo-server "$@"
fi

exec "$@"
