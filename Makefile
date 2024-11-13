#!/usr/bin/make
# Makefile readme (ru): <http://linux.yaroslavl.ru/docs/prog/gnu_make_3-79_russian_manual.html>
# Makefile readme (en): <https://www.gnu.org/software/make/manual/html_node/index.html#SEC_Contents>

SHELL = /bin/sh


ENV_FILE = .local.env
REGISTRY_HOST = registry.alga-corp.ru
REGISTRY_PATH = it59com/carer
IMAGES_PREFIX := $(shell basename $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))))

PUBLISH_TAGS = latest
PULL_TAG = latest

# Important: Local images naming should be in docker-compose naming style

APP_IMAGE = $(REGISTRY_HOST)/$(REGISTRY_PATH)/api
APP_IMAGE_LOCAL_TAG = $(IMAGES_PREFIX)_api
APP_IMAGE_DOCKERFILE = ./../docker-images/api/Dockerfile
APP_IMAGE_CONTEXT = ../..

NGINX_IMAGE = $(REGISTRY_HOST)/$(REGISTRY_PATH)/nginx
NGINX_IMAGE_LOCAL_TAG = $(IMAGES_PREFIX)_nginx
NGINX_IMAGE_DOCKERFILE = ./../docker-images/nginx/Dockerfile
NGINX_IMAGE_CONTEXT = ../..

POSTGRES_IMAGE = $(REGISTRY_HOST)/$(REGISTRY_PATH)/postgres
POSTGRES_IMAGE_LOCAL_TAG = $(IMAGES_PREFIX)_postgres
POSTGRES_IMAGE_DOCKERFILE = ./../docker-images/postgres/Dockerfile
POSTGRES_IMAGE_CONTEXT = ./provision/bin/docker-images/postgres

APP_CONTAINER_NAME := api

docker_bin := $(shell command -v docker 2> /dev/null)
docker_compose_bin := $(shell command -v docker-compose 2> /dev/null)
docker_compose_config := provision/local/docker-compose.yml

all_images = $(APP_IMAGE) \
             $(APP_IMAGE_LOCAL_TAG) \
             $(NGINX_IMAGE) \
             $(NGINX_IMAGE_LOCAL_TAG)

ifeq "$(REGISTRY_HOST)" "registry.dev.alga-corp.ru"
	docker_login_hint ?= "\n\
	**************************************************************************************\n\
	* Make your own auth token here: <https://gitlab.com/profile/personal_access_tokens> *\n\
	**************************************************************************************\n"
endif

.PHONY : help pull build push login test clean \
         app-pull app app-push update-schema\
         sources-pull sources sources-push\
         nginx-pull nginx nginx-push\
         up down restart shell install
.DEFAULT_GOAL : help

# --- [ Application ] -------------------------------------------------------------------------------------------------

app-pull: ## Application - pull latest Docker image (from remote registry)
	-$(docker_bin) pull "$(APP_IMAGE):$(PULL_TAG)"

app: app-pull ## Application - build Docker image locally
	$(docker_bin) build \
	  --cache-from "$(APP_IMAGE):$(PULL_TAG)" \
	  --tag "$(APP_IMAGE_LOCAL_TAG)" \
	  -f $(APP_IMAGE_DOCKERFILE) $(APP_IMAGE_CONTEXT)

app-push: app-pull ## Application - tag and push Docker image into remote registry
	$(docker_bin) build \
	  --cache-from "$(APP_IMAGE):$(PULL_TAG)" \
	  $(foreach tag_name,$(PUBLISH_TAGS),--tag "$(APP_IMAGE):$(tag_name)") \
	  -f $(APP_IMAGE_DOCKERFILE) $(APP_IMAGE_CONTEXT);
	$(foreach tag_name,$(PUBLISH_TAGS),$(docker_bin) push "$(APP_IMAGE):$(tag_name)";)

# --- [ Nginx ] -------------------------------------------------------------------------------------------------------

nginx-pull: ## Nginx - pull latest Docker image (from remote registry)
	-$(docker_bin) pull "$(NGINX_IMAGE):$(PULL_TAG)"

nginx: nginx-pull ## Nginx - build Docker image locally
	$(docker_bin) build \
	  --cache-from "$(NGINX_IMAGE):$(PULL_TAG)" \
	  --tag "$(NGINX_IMAGE_LOCAL_TAG)" \
	  -f $(NGINX_IMAGE_DOCKERFILE) $(NGINX_IMAGE_CONTEXT)

nginx-push: nginx-pull ## Nginx - tag and push Docker image into remote registry
	$(docker_bin) build \
	  --cache-from "$(NGINX_IMAGE):$(PULL_TAG)" \
	  $(foreach tag_name,$(PUBLISH_TAGS),--tag "$(NGINX_IMAGE):$(tag_name)") \
	  -f $(NGINX_IMAGE_DOCKERFILE) $(NGINX_IMAGE_CONTEXT);
	$(foreach tag_name,$(PUBLISH_TAGS),$(docker_bin) push "$(NGINX_IMAGE):$(tag_name)";)

# --- [ PostgreSQL ] -------------------------------------------------------------------------------------------------------
postgres-shell: ## PostgreSQL - pull latest Docker image (from remote registry)
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} run postgres bash


postgres-pull: ## PostgreSQL - pull latest Docker image (from remote registry)
	-$(docker_bin) pull "$(POSTGRES_IMAGE):$(PULL_TAG)"

postgres: postgres-pull ## PostgreSQL - build Docker image locally
	$(docker_bin) build \
	  --cache-from "$(POSTGRES_IMAGE):$(PULL_TAG)" \
	  --tag "$(POSTGRES_IMAGE_LOCAL_TAG)" \
	  -f $(POSTGRES_IMAGE_DOCKERFILE) $(POSTGRES_IMAGE_CONTEXT)

postgres-dump: ## PostgreSQL - dump 
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} exec postgres pg_dump 
postgres-psql: ## PostgreSQL - psql 
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} exec postgres psql

postgres-push: postgres-pull ## PostgreSQL - tag and push Docker image into remote registry
	$(docker_bin) build \
	  --cache-from "$(POSTGRES_IMAGE):$(PULL_TAG)" \
	  $(foreach tag_name,$(PUBLISH_TAGS),--tag "$(POSTGRES_IMAGE):$(tag_name)") \
	  -f $(POSTGRES_IMAGE_DOCKERFILE) $(POSTGRES_IMAGE_CONTEXT);
	$(foreach tag_name,$(PUBLISH_TAGS),$(docker_bin) push "$(POSTGRES_IMAGE):$(tag_name)";)

# This will output the help for each task. thanks to https://marmelab.com/blog/2016/02/29/auto-documented-makefile.html
help: ## Show this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)
	@echo "\n  Allowed for overriding next properties:\n\n\
	    PULL_TAG - Tag for pulling images before building own\n\
	              ('latest' by default)\n\
	    PUBLISH_TAGS - Tags list for building and pushing into remote registry\n\
	                   (delimiter - single space, 'latest' by default)\n\n\
	  Usage example:\n\
	    make PULL_TAG='v1.2.3' PUBLISH_TAGS='latest v1.2.3 test-tag' app-push\n\n\
	  Usage push-api:\n\
	    make push-api PUBLISH_TAGS='0.0.1' \n\
	  Usage push-nginx:\n\
	    make push-nginx PUBLISH_TAGS='0.0.1' \n"

clean: ## Remove images from local registry
	-"$(docker_compose_bin)" down -v
	$(foreach image,$(all_images),$(docker_bin) rmi -f $(image);)

# --- [ Development tasks ] -------------------------------------------------------------------------------------------
---------------: ## ---------------
push-api: ## push registry API container PUBLISH_TAGS=version
	@echo "push api VERSION=$(PUBLISH_TAGS)"

	$(docker_bin) build -t registry.dev.alga-corp.ru/crm/api:$(PUBLISH_TAGS)  -t registry.dev.alga-corp.ru/crm/api:latest -f provision/docker-images/api/Dockerfile.prod .
  	 docker image tag api:$(PUBLISH_TAGS) registry.dev.alga-corp.ru/crm
    docker image tag api:latest registry.dev.alga-corp.ru/crm
  	docker push  registry.dev.alga-corp.ru/crm/api:$(PUBLISH_TAGS)
  	docker push  registry.dev.alga-corp.ru/crm/api:latest'

push-nginx: ## push registry NGINX and FRONT container PUBLISH_TAGS=version
	$(docker_bin) build -t registry.dev.alga-corp.ru/crm/nginx:$(PUBLISH_TAGS)  -t registry.dev.alga-corp.ru/crm/nginx:latest -f provision/docker-images/nginx/Dockerfile.prod .
  	$(docker_bin) image tag nginx:$(PUBLISH_TAGS) registry.dev.alga-corp.ru/crm
  	$(docker_bin) image tag nginx:latest registry.dev.alga-corp.ru/crm
  	docker push  registry.dev.alga-corp.ru/crm/nginx:$(PUBLISH_TAGS)
  	docker push  registry.dev.alga-corp.ru/crm/nginx:latest
---------------: ## ---------------
init: build install ## Make full application initialization (install, seed, build assets, etc)

build: down ## Build all containers
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} build --compress --parallel

build-api: down ## Build API container
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} build api

build-front: down-front ## Build Frontend container
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} build frontend


build-websocket: ## Build WebSocket container
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} build websockets

shell-api: ## Start shell into backend container
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE}  exec api /bin/bash

shell-front: ## Start shell into frontend container
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} stop frontend


up: ## Start all containers (in background) for development
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} up --no-recreate -d

down: ## Stop all started for development containers
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} down
down-front: ## Stop frontend development containers
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} stop frontend



restart: ## Restart all started for development containers
	make down
	make up
restart-front: ## Restart front for development
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} stop frontend
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} up 

socket:  ## WebSocket shell
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} exec websockets bash

socket-make: ## Make WebSocket container
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} build websockets 


logs: ## Start shell into application container
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} logs "$(APP_CONTAINER_NAME)"

shell: up ## Start shell into application container
	"$(docker_compose_bin)" -f "$(docker_compose_config)"  --env-file ${ENV_FILE} exec "$(APP_CONTAINER_NAME)" bash

install: up   ## Install application dependencies into application container
	"$(docker_compose_bin)" -f "$(docker_compose_config)"  --env-file ${ENV_FILE} exec "$(APP_CONTAINER_NAME)" composer install  
	make migrate

migrate: cache-clear ## Migrate database
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} exec "$(APP_CONTAINER_NAME)" php artisan doctrine:schema:create --no-interaction -vvv
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} exec "$(APP_CONTAINER_NAME)" php artisan migrate --force --no-interaction -vvv
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} exec "$(APP_CONTAINER_NAME)" php artisan db:seed --force -vvv

update-schema: ## Update  schema and clear cache
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} exec "$(APP_CONTAINER_NAME)" php artisan cache:clear
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} exec "$(APP_CONTAINER_NAME)" php artisan doctrine:clear:metadata:cache
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} exec "$(APP_CONTAINER_NAME)" php artisan doctrine:clear:query:cache
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} exec "$(APP_CONTAINER_NAME)" php artisan doctrine:clear:result:cache
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} exec "$(APP_CONTAINER_NAME)" ./update-Entities.sh
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} exec "$(APP_CONTAINER_NAME)" php artisan doctrine:schema:update
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} exec "$(APP_CONTAINER_NAME)" php artisan doctrine:generate:proxies -q
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} exec "$(APP_CONTAINER_NAME)" chmod 777 Infrastructure/Doctrine/Proxies -R
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} exec "$(APP_CONTAINER_NAME)" php artisan doctrine:schema:validate


cache-clear: ## Clear all caches and make config cache
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} exec "$(APP_CONTAINER_NAME)" php artisan cache:clear

	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} exec "$(APP_CONTAINER_NAME)" composer dump-autoload

test: up ## Execute application tests
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} exec "$(APP_CONTAINER_NAME)" composer test

testwithargs: up ## Execute application tests
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} exec "$(APP_CONTAINER_NAME)" ./vendor/phpunit/phpunit/phpunit  $(testargs)

lint: up ## Execute application tests
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} exec "$(APP_CONTAINER_NAME)" ./vendor/tightenco/tlint/bin/tlint lint ./

docs: ## Generate API docs
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} exec "$(APP_CONTAINER_NAME)" php ./Application/Lumen/artisan make-api-doc user
	"$(docker_compose_bin)" -f "$(docker_compose_config)" --env-file ${ENV_FILE} exec "$(APP_CONTAINER_NAME)" php ./Application/Lumen/a