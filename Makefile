include .env
export

.PHONY: start stop restart build \
	ssh env \
	truncate migrate seed reset-db \
	node-dev node-install node-test node-watch \
	composer-install dump-autoload \
	keygen \
	deploy phpunit build-frontend good

DC := docker-compose exec
FPM := $(DC) php-fpm
NODE := $(DC) node yarn
ARTISAN := $(FPM) php artisan
MYSQL := $(DC) -T mysql

start:
	@docker-compose up -d

stop:
	@docker-compose down

restart: stop start

build:
	@docker-compose build --pull

env:
	cp ./.env.example ./.env

ssh:
	@$(FPM) bash

keygen:
	@$(ARTISAN) key:generate

truncate:
	@$(ARTISAN) db:wipe

migrate:
	@$(ARTISAN) migrate

seed:
	@$(ARTISAN) db:seed

build-frontend:
	@$(ARTISAN) platform:frontend

tinker:
	@$(ARTISAN) tinker

reset-db: truncate migrate seed

node-install:
	@$(NODE) install

node-watch:
	@$(NODE) run watch

node-watch-poll:
	@$(NODE) run watch-poll

node-dev:
	@$(NODE) run dev

node-test:
	@$(NODE) run test

composer-install:
	@$(FPM) composer install

phpunit:
	@$(FPM) vendor/bin/phpunit -c phpunit.xml --testsuite Feature,Unit --log-junit reports/phpunit.xml --coverage-html reports/coverage --coverage-clover reports/coverage.xml

phpunit-haapi:
	@$(FPM) vendor/bin/phpunit -c phpunit.xml --testsuite Haapi --log-junit reports/phpunit.xml --coverage-html reports/coverage --coverage-clover reports/coverage.xml

phpcs:
	@$(FPM) vendor/bin/phpcs --report=checkstyle --standard=PSR2 --extensions=php --ignore='*/database/*, */config/*, */migrations/*, */views/*, */lang/*, autoload.php' app modules

phpcbf:
	@$(FPM) vendor/bin/phpcbf --report=checkstyle --standard=PSR2 --extensions=php --ignore='*/database/*, */config/*, */migrations/*, */views/*, */lang/*, autoload.php' app modules

phpmd:
	@$(FPM) vendor/bin/phpmd app text codesize,unusedcoderules.xml

deploy: env start composer-install keygen migrate seed build-frontend node-install node-dev

good:
	echo "\e[32mMaking everything GOOD is in progress" \
	&& make composer-install && make build-frontend && make node-install && make node-dev && make migrate && make seed \
	&& echo "\e[32mEverything is GOOD now"

dump-autoload:
	@$(FPM) composer dump-autoload

targeting-import-all: targeting-import-types \
	targeting-import-age-groups \
	targeting-import-cities \
	targeting-import-dma \
	targeting-import-states \
	targeting-import-zipcodes \
	targeting-import-genres \
	targeting-import-audiences \
	targeting-import-devices \
	targeting-import-disney-audiences \
	targeting-import-guaranteed-age-groups \

targeting-import-types:
	@$(ARTISAN) targeting:types:import

targeting-import-age-groups:
	@$(ARTISAN) targeting:age-groups:import

targeting-import-guaranteed-age-groups:
	@$(ARTISAN) targeting:guaranteed-age-groups:import

targeting-import-cities:
	@$(ARTISAN) targeting:cities:import

targeting-import-dma:
	@$(ARTISAN) targeting:dma:import

targeting-import-states:
	@$(ARTISAN) targeting:states:import

targeting-import-zipcodes:
	@$(ARTISAN) targeting:zipcodes:import

targeting-import-genres:
	@$(ARTISAN) targeting:genres:import

targeting-import-audiences:
	@$(ARTISAN) targeting:audiences:import

targeting-import-devices:
	@$(ARTISAN) targeting:devices:import

targeting-import-disney-audiences:
	@$(ARTISAN) targeting:disney-audiences:import

targeting-refresh: targeting-delete-all \
	targeting-import-all

alert-send:
	@$(ARTISAN) platform:alert:send

report-scheduled-send-daily:
	@$(ARTISAN) platform:report:scheduled:send daily

report-scheduled-send-weekly:
	@$(ARTISAN) platform:report:scheduled:send weekly

report-scheduled-send-monthly:
	@$(ARTISAN) platform:report:scheduled:send monthly

clear-creatives:
	@$(ARTISAN) platform:creative:clear

xdebug-enable:
	./infrastructure/docker/xdebug.sh enable

xdebug-disable:
	./infrastructure/docker/xdebug.sh disable

db-dump-download:
	$(MYSQL) mysqldump --no-tablespaces --set-gtid-purged=OFF -h $(REMOTE_DB_HOST) -u $(REMOTE_DB_USERNAME) -p$(REMOTE_DB_PASSWORD) $(REMOTE_DB_DATABASE) > storage/database.sql

db-dump-import:
	$(MYSQL) mysql -u $(DB_USERNAME) -p$(DB_PASSWORD) $(DB_DATABASE) < storage/database.sql

db-dump-cleanup:
	rm storage/database.sql

db-dump: db-dump-download \
	db-dump-import \
	db-dump-cleanup
