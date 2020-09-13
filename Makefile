build:
	docker-compose build

install:
	composer install

check:
	vendor/bin/phpcs

test:
	vendor/bin/phpunit

run:
	docker-compose up
