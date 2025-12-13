up:
	docker compose -f .docker/compose.yaml up -d; \
	docker compose -f .docker/compose.yaml run --rm --remove-orphans php-dev composer install

down:
	docker compose -f .docker/compose.yaml down

pre-release:
	docker compose -f .docker/compose.yaml run --rm --remove-orphans php-dev composer validate; \
	./vendor/bin/export-ignore

check-dockerfile:
	docker run --rm -i ghcr.io/hadolint/hadolint < .docker/Dockerfile