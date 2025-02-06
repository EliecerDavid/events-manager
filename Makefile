#! /usr/bin/make
help:           ## show help
	@cat Makefile | grep "##." | sed '2d;s/##//;s/://'

up:             ## run containers
	docker compose up -d --remove-orphans && sleep 5

down:           ## stop and remove containers
	docker compose stop

build:          ## build containers
	docker compose build

test:           ## run tests
	docker compose run --rm app php artisan test

coverage:       ## run test coverage report
	docker compose run --rm app php artisan test --coverage

pint_analyze:   ## analyze errors of style of code with Laravel pint
	docker compose run --rm app ./vendor/bin/pint -v

pint_repair:    ## repair style of code with Laravel pint
	docker compose run --rm app ./vendor/bin/pint --repair
