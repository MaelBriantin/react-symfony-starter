DOCKER_COMPOSE = docker compose
DOCKER_RUN = $(DOCKER_COMPOSE) run --rm

.PHONY: setup
setup: init-react init-caddy

.PHONY: init-caddy
init-caddy: build-caddy up-caddy

.PHONY: build-caddy
build-caddy:
	$(DOCKER_COMPOSE) build --no-cache caddy

.PHONY: up-caddy
up-caddy:
	$(DOCKER_COMPOSE) up -d caddy

.PHONY: init-react
init-react: build-react pnpm-install up-react

.PHONY: build-react
build-react:
	$(DOCKER_COMPOSE) build --no-cache react

.PHONY: up-react
up-react:
	$(DOCKER_COMPOSE) up -d react

.PHONY: pnpm-install
pnpm-install:
	$(DOCKER_RUN) react pnpm install

.PHONY: clean-react
clean-react:
	$(DOCKER_RUN) react rm -rf node_modules
	$(DOCKER_RUN) react rm -rf .pnpm-store
	$(DOCKER_RUN) react rm -rf dist