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
install: init-env init-database init-php init-react init-caddy ## Initialize the complete stack

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

# --- Database Service ---

.PHONY: init-database
init-database: build-database up-database ## Initialize Database container (build, up)

.PHONY: build-database
build-database: ## Build Database container
	$(DOCKER_COMPOSE) build --no-cache database

.PHONY: up-database
up-database: ## Start Database container in detached mode
	$(DOCKER_COMPOSE) up -d database

.PHONY: logs-database
logs-database: ## Show Database container logs
	$(DOCKER_COMPOSE) logs -f database

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

# --- React Service ---

.PHONY: init-react
init-react: build-react pnpm-install up-react ## Initialize React container (build, install deps, up)

.PHONY: build-react
build-react: ## Build React container
	$(DOCKER_COMPOSE) build --no-cache react

.PHONY: up-react
up-react: ## Start React container in detached mode
	$(DOCKER_COMPOSE) up -d react

.PHONY: pnpm-install
pnpm-install: ## Install pnpm dependencies in React container
	$(DOCKER_RUN) react pnpm install

.PHONY: pnpm-add
pnpm-add: ## Add a pnpm package
	@read -p "Enter the package name to add: " package; \
	if [ -z "$$package" ]; then \
		echo "Package name cannot be empty"; \
		exit 1; \
	fi; \
	$(DOCKER_RUN) react pnpm add "$$package"; \
	echo "Package '$$package' added successfully";

.PHONY: clean-react
clean-react: ## Clean React container (node_modules, pnpm-store, dist)
	$(DOCKER_RUN) react rm -rf node_modules
	$(DOCKER_RUN) react rm -rf .pnpm-store
	$(DOCKER_RUN) react rm -rf dist

.PHONY: logs-react
logs-react: ## Show React container logs
	$(DOCKER_COMPOSE) logs -f react

.PHONY: shell-react
shell-react: ## Open a shell in the React container
	$(DOCKER_RUN) react sh

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
clean-stack: clean-react clean-php ## Clean generated files in PHP and React containers

.PHONY: down
down: ## Stop and remove all containers, networks, and volumes
	$(DOCKER_COMPOSE) down --remove-orphans --volumes

.PHONY: prune
prune: ## Clean up unused Docker resources (images, networks, volumes)
	docker system prune -af --volumes

.PHONY: hard-reset
hard-reset: clean-stack down prune install ## Hard reset: Clean, down, prune Docker, and reinstall stack
	@echo "Hard reset completed. Stack cleaned, containers/volumes removed, Docker pruned."
	@echo "Stack has been reinitialized. Run 'make up-database up-php up-react up-caddy' or relevant 'up-*' targets to start services."