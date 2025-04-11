DOCKER_COMPOSE = docker compose

.PHONY: setup
setup: build up

.PHONY: reset
reset: down setup

.PHONY: hard-reset
reset: down prune setup

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