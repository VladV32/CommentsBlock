#!/usr/bin/make
include .env
export $(shell sed 's/=.*//' .env)

THIS_FILE := $(lastword $(MAKEFILE_LIST))

.PHONY : up-all

.DEFAULT_GOAL := help

help:
	make -pRrq  -f $(THIS_FILE) : 2>/dev/null | awk -v RS= -F: '/^# File/,/^# Finished Make data base/ {if ($$1 !~ "^[#.]") {print $$1}}' | sort | egrep -v -e '^[^[:alnum:]]' -e '^$@$$'
up:
	docker-compose up -d
build:
	docker-compose build
	docker-compose up -d
build-clear:
	docker-compose build --no-cache
	docker-compose up -d
down:
	docker-compose down
init:
	docker-compose exec --user "${WWWUSER}:${WWWGROUP}" php bash -c "composer install"
	docker-compose exec php bash -c "php artisan key:generate"
	docker-compose exec php bash -c "php artisan migrate:fresh --seed"
	docker-compose exec php bash -c "composer install --working-dir=tools/php-cs-fixer"
	docker-compose exec php bash -c "composer install --working-dir=tools/phpstan"
	docker-compose exec php bash -c "composer install --working-dir=tools/phpmetrics"
	docker-compose exec php bash -c "npm i"
	docker-compose exec php bash -c "npm run build"
front:
	docker-compose exec php bash -c "npm i"
	docker-compose exec php bash -c "npm run dev"
clear:
	docker-compose exec php bash -c "php artisan optimize:clear"
docs:
	docker-compose exec php bash -c "php artisan scribe:generate"
test:
	docker-compose exec php bash -c "composer generate_meta_helpers"
	docker-compose exec php bash -c "composer fix_style"
	docker-compose exec php bash -c "composer check_code"
	docker-compose exec php bash -c "php artisan test --parallel --recreate-databases"
migrate:
	docker-compose exec php bash -c "php artisan migrate"
rollback:
	docker-compose exec php bash -c "php artisan migrate:rollback"
metrics:
	docker-compose exec php bash -c "php artisan optimize:clear"
	sh -c 'docker-compose exec --user "${WWWUSER}:${WWWGROUP}" php bash -c "composer phpmetrics"'
	open metrics-report/index.html
unit:
	docker-compose exec php bash -c "php artisan optimize:clear"
	docker-compose exec php bash -c "php artisan test --parallel --recreate-databases"
queue:
	docker-compose exec --user "${WWWUSER}:${WWWGROUP}" php bash -c "composer post-queue-work"
