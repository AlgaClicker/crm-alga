#!/usr/bin/env bash
set -ex



for i in "$@"
do
case $i in
    -env=*|--environment=*)
    ENV="${i#*=}"
    shift # past argument=value
    ;;
esac
done

if [[ -z $ENV ]]
then
   echo 'No Env File.'
   exit 0
fi


export $( cat  $ENV| grep -vE "^(#.*|\s*)$")


#TODO: check env file exists
COMPOSE_PROJECT_NAME=${APP_NAME} docker-compose -f provision/prod/docker-compose.yml --env-file $ENV  up -d --build --force-recreate
