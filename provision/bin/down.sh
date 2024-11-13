#!/usr/bin/env bash
set -ex

SCRIPT_PATH="`dirname \"$0\"`"
cd ${SCRIPT_PATH}
cd ../prod

#TODO: check env file exists
COMPOSE_PROJECT_NAME=${1} docker-compose --env-file ${1}.env down
