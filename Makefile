up:
	docker compose -f .docker/compose.yaml up -d --build
	docker compose -f .docker/compose.yaml run --rm --remove-orphans php-dev composer install

down:
	docker compose -f .docker/compose.yaml down

shell:
	docker exec -it php-code-sniffs-dev /bin/sh

check-dockerfile:
	docker run --rm -i ghcr.io/hadolint/hadolint < .docker/Dockerfile

lint:
	php ./vendor/bin/phpcs
	php ./vendor/bin/phpstan analyze
	php ./vendor/bin/phpcs-check-feature-completeness

test-cs:
	cd ../cs-test/ && XDEBUG_MODE=coverage php ./vendor/bin/phpunit --filter AntipinCS

check-build:
	composer validate --strict
	php ./vendor/bin/export-ignore

test-all: lint test-cs check-build