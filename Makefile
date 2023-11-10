.PHONY: run-php-7.4
run-php-7.4:
	docker-compose run php74 sh

.PHONY: run-php-8.1
run-php-8.1:
	docker-compose run php81 sh
