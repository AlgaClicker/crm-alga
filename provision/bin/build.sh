#!/usr/bin/env bash
#set -ex

SCRIPT_PATH="`dirname \"$0\"`"
cd ${SCRIPT_PATH}
cd ../..


rm backend/Application/Lumen/storage/tmp-entities -fr
rm backend/Application/Lumen/storage/logs/ -fr
rm backend/Application/Lumen/tmp -fr
mkdir -p backend/Application/Lumen/storage/logs


for i in "$@"
do
case $i in

    -v=*|--version=*)
    VERSION="${i#*=}"
    shift # past argument=value
    ;;
esac
done

if [[ -z $VERSION ]]
then
   echo 'Version number is not specified.'
   exit 0
fi

#  docker build -t registry.dev.alga-corp.ru/crm/frontend:$VERSION -t registry.dev.alga-corp.ru/crm/frontend:latest -f provision/docker-images/front/Dockerfile.prod .
#  docker image tag frontend:$VERSION registry.dev.alga-corp.ru/crm
#  docker image tag frontend:latest registry.dev.alga-corp.ru/crm
#  docker push  registry.dev.alga-corp.ru/crm/frontend:$VERSION
#  docker push  registry.dev.alga-corp.ru/crm/frontend:latest

  docker build -t registry.dev.alga-corp.ru/crm/api:$VERSION -t registry.dev.alga-corp.ru/crm/api:latest -f provision/docker-images/api/Dockerfile.prod .
  docker image tag api:$VERSION registry.dev.alga-corp.ru/crm
  docker image tag api:latest registry.dev.alga-corp.ru/crm
  docker push  registry.dev.alga-corp.ru/crm/api:$VERSION
  docker push  registry.dev.alga-corp.ru/crm/api:latest

  docker build -t  registry.dev.alga-corp.ru/crm/nginx:$VERSION -t registry.dev.alga-corp.ru/crm/nginx:latest -f provision/docker-images/nginx/Dockerfile.prod .
  docker image tag nginx registry.dev.alga-corp.ru/crm
  docker push  registry.dev.alga-corp.ru/crm/nginx:$VERSION
  docker push  registry.dev.alga-corp.ru/crm/nginx:latest


  docker build -t registry.dev.alga-corp.ru/crm/websockets  -f provision/docker-images/websockets/Dockerfile.prod ./provision/docker-images/websockets
  docker image tag websockets registry.dev.alga-corp.ru/crm
  docker push  registry.dev.alga-corp.ru/crm/websockets

