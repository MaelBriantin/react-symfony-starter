# Colors for help target using tput (more portable)
BLUE := $(shell tput setaf 4)
RESET := $(shell tput sgr0)

# Docker Commands
DOCKER_COMPOSE = docker compose
DOCKER_RUN = $(DOCKER_COMPOSE) run --rm

.DEFAULT_GOAL := help

# --- Help & Main Installation ---

.PHONY: help
help: ## Show this help message
	@printf "Usage: make %s[target]%s\n\n" "$(BLUE)" "$(RESET)"
	@printf "Targets:\n"
	@awk -v blue="$(BLUE)" -v reset="$(RESET)" \
	    'BEGIN {FS = ":.*?## "; target_to_find="install"} \
	    /^[a-zA-Z_-]+:.*?## / && $$1 == target_to_find { \
	        printf "  %s%-18s%s %s\n", blue, $$1, reset, $$2; \
	        exit \
	    }' $(MAKEFILE_LIST)
	@printf "\n"
	@awk 'BEGIN {FS = ":.*?## "; target_to_exclude="install"} \
	    /^[a-zA-Z_-]+:.*?## / && $$1 != target_to_exclude { \
	        printf "%s\t%s\n", $$1, $$2 \
	    }' $(MAKEFILE_LIST) | \
	sort -f -t$$'\t' -k1,1 | \
	awk -v blue="$(BLUE)" -v reset="$(RESET)" 'BEGIN {FS = "\t"} { \
	    printf "  %s%-18s%s %s\n", blue, $$1, reset, $$2 \
	}'


.PHONY: install
install: init-env init-mysql init-php init-deno init-caddy ## Initialize the complete stack

.PHONY: init-env
init-env: ## Initialize environment files from example
	@if [ ! -f .env ]; then \
		cp .env.example .env; \
		SECRET=$$(openssl rand -hex 16); \
		if [ "$$OSTYPE" = "darwin"* ]; then \
			sed -i '' "s/^APP_SECRET=$$/APP_SECRET=$$SECRET/" .env; \
		else \
			sed -i "s/^APP_SECRET=$$/APP_SECRET=$$SECRET/" .env; \
		fi; \
		echo "Created .env file from .env.example with new APP_SECRET"; \
	else \
		echo ".env file already exists"; \
	fi

# --- mysql Service ---

.PHONY: init-mysql
init-mysql: build-mysql up-mysql ## Initialize mysql container (build, up)

.PHONY: build-mysql
build-mysql: ## Build mysql container
	$(DOCKER_COMPOSE) build --no-cache mysql

.PHONY: up-mysql
up-mysql: ## Start mysql container in detached mode
	$(DOCKER_COMPOSE) up -d mysql

.PHONY: logs-mysql
logs-mysql: ## Show mysql container logs
	$(DOCKER_COMPOSE) logs -f mysql

# --- PHP Service ---

.PHONY: init-php
init-php: build-php composer-install up-php ## Initialize PHP container (build, install deps, up)

.PHONY: build-php
build-php: ## Build PHP container
	$(DOCKER_COMPOSE) build --no-cache php

.PHONY: up-php
up-php: ## Start PHP container in detached mode
	$(DOCKER_COMPOSE) up -d php

.PHONY: composer-install
composer-install: ## Install Composer dependencies in PHP container
	$(DOCKER_RUN) php composer install

.PHONY: composer-require
composer-require: ## Add a Composer package and update dependencies
	@read -p "Enter the package name to add: " package; \
	if [ -z "$$package" ]; then \
		echo "Package name cannot be empty"; \
		exit 1; \
	fi; \
	$(DOCKER_RUN) php composer require "$$package"; \
	echo "Package '$$package' added successfully"; \
	$(DOCKER_RUN) php composer update;

.PHONY: clean-php
clean-php: ## Clean PHP container (vendor, cache)
	$(DOCKER_RUN) php rm -rf vendor/
	$(DOCKER_RUN) php rm -rf var/cache/*

.PHONY: logs-php
logs-php: ## Show PHP container logs
	$(DOCKER_COMPOSE) logs -f php

.PHONY: shell-php
shell-php: ## Open a shell in the PHP container
	$(DOCKER_RUN) php sh

# --- Deno Service ---

.PHONY: init-deno
init-deno: build-deno deno-install up-deno ## Initialize Deno container (build, install deps, up)

.PHONY: build-deno
build-deno: ## Build React container
	$(DOCKER_COMPOSE) build --no-cache deno

.PHONY: up-deno
up-deno: ## Start React container in detached mode
	$(DOCKER_COMPOSE) up -d deno

.PHONY: deno-install
deno-install: ## Install deno dependencies in React container
	$(DOCKER_RUN) deno deno cache --node-modules-dir=auto src/main.ts

.PHONY: deno-add
deno-add: ## Add a deno package
	@read -p "Enter the package name to add: " package; \
	if [ -z "$$package" ]; then \
		echo "Package name cannot be empty"; \
		exit 1; \
	fi; \
	$(DOCKER_RUN) deno deno add "$$package"; \
	echo "Package '$$package' added successfully";

.PHONY: clean-deno
clean-deno: ## Clean React container (node_modules, deno-store, dist)
	$(DOCKER_RUN) deno rm -rf node_modules
	$(DOCKER_RUN) deno rm -rf .deno-store
	$(DOCKER_RUN) deno rm -rf dist

.PHONY: logs-deno
logs-deno: ## Show React container logs
	$(DOCKER_COMPOSE) logs -f deno

.PHONY: shell-deno
shell-deno: ## Open a shell in the React container
	$(DOCKER_RUN) deno sh

# --- Caddy Service ---

.PHONY: init-caddy
init-caddy: build-caddy up-caddy ## Initialize Caddy container (build, up)

.PHONY: build-caddy
build-caddy: ## Build Caddy container
	$(DOCKER_COMPOSE) build --no-cache caddy

.PHONY: up-caddy
up-caddy: ## Start Caddy container in detached mode
	$(DOCKER_COMPOSE) up -d caddy

.PHONY: logs-caddy
logs-caddy: ## Show Caddy container logs
	$(DOCKER_COMPOSE) logs -f caddy

# --- Stack Management ---

.PHONY: ps
ps: ## Show running containers status
	$(DOCKER_COMPOSE) ps

.PHONY: logs
logs: ## Show logs from all containers
	$(DOCKER_COMPOSE) logs -f

.PHONY: rebuild
rebuild: ## Rebuild images and restart containers
	$(DOCKER_COMPOSE) build
	$(DOCKER_COMPOSE) up -d --force-recreate --remove-orphans

.PHONY: restart
restart: ## Restart all containers (down + up)
	$(DOCKER_COMPOSE) down --remove-orphans
	$(DOCKER_COMPOSE) up -d

# --- Cleaning & Reset ---

.PHONY: clean-stack
clean-stack: clean-deno clean-php ## Clean generated files in PHP and React containers

.PHONY: down
down: ## Stop and remove all containers, networks, and volumes
	$(DOCKER_COMPOSE) down --remove-orphans --volumes

.PHONY: prune
prune: ## Clean up unused Docker resources (images, networks, volumes)
	docker system prune -af --volumes

.PHONY: hard-reset
hard-reset: clean-stack down prune install ## Hard reset: Clean, down, prune Docker, and reinstall stack
	@echo "Hard reset completed. Stack cleaned, containers/volumes removed, Docker pruned."
	@echo "Stack has been reinitialized. Run 'make up-mysql up-php up-deno up-caddy' or relevant 'up-*' targets to start services."