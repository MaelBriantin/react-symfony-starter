DOCKER_COMPOSE = docker-compose

.PHONY: setup
setup: build sync-node-modules

.PHONY: build
build:
	$(DOCKER_COMPOSE) up -d --build

.PHONY: build-no-cache
build-no-cache:
	$(DOCKER_COMPOSE) up -d --build --no-cache

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

.PHONY: sync-node-modules
sync-node-modules:
	docker cp react:/var/www/pnpm-lock.yaml ./app/react/pnpm-lock.yaml && \
	cd app/react && \
	pnpm install --prefer-offline 