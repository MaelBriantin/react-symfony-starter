DOCKER_COMPOSE = docker compose

.PHONY: setup
setup: build up

.PHONY: reset
reset: down build up

.PHONY: build
build:
	$(DOCKER_COMPOSE) build --no-cache

.PHONY: ps
ps:
	$(DOCKER_COMPOSE) ps

.PHONY: up
up:
	$(DOCKER_COMPOSE) up -d

.PHONY: down
down:
	$(DOCKER_COMPOSE) down -v --remove-orphans

.PHONY: prune
prune: down
	docker system prune -f

.PHONY: onpm-install
pnpm-install:
	cd app/react && \
	rm -rf node_modules && \
	pnpm install

.PHONY: composer-install
composer-install:
	docker exec -it php-fpm composer install --prefer-dist --no-dev --no-interaction