# Colors for help target using tput (more portable)
BLUE := $(shell tput setaf 4)
RESET := $(shell tput sgr0)

DOCKER_COMPOSE = docker compose
DOCKER_RUN = $(DOCKER_COMPOSE) run --rm

.PHONY: help
help: ## Show this help message
	@printf "Usage: make %s[target]%s\n\n" "$(BLUE)" "$(RESET)"
	@printf "Targets:\n"
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  %s%-15s%s %s\n", "$(BLUE)", $$1, "$(RESET)", $$2}' $(MAKEFILE_LIST)

.PHONY: install
install: init-env init-react init-database init-php init-caddy ## Initialize the complete stack

.PHONY: init-database
init-database: build-database up-database ## Initialize Database container

.PHONY: build-database
build-database: ## Build Database container
	$(DOCKER_COMPOSE) build --no-cache database

.PHONY: up-database
up-database: ## Start Database container
	$(DOCKER_COMPOSE) up -d database

.PHONY: init-caddy
init-caddy: build-caddy up-caddy ## Initialize Caddy container

.PHONY: build-caddy
build-caddy: ## Build Caddy container
	$(DOCKER_COMPOSE) build --no-cache caddy

.PHONY: up-caddy
up-caddy: ## Start Caddy container
	$(DOCKER_COMPOSE) up -d caddy

.PHONY: init-react
init-react: build-react pnpm-install up-react ## Initialize React container

.PHONY: build-react
build-react: ## Build React container
	$(DOCKER_COMPOSE) build --no-cache react

.PHONY: up-react
up-react: ## Start React container
	$(DOCKER_COMPOSE) up -d react

.PHONY: pnpm-install
pnpm-install: ## Install pnpm dependencies in React container
	$(DOCKER_RUN) react pnpm install

.PHONY: clean-react
clean-react: ## Clean React container
	$(DOCKER_RUN) react rm -rf node_modules
	$(DOCKER_RUN) react rm -rf .pnpm-store
	$(DOCKER_RUN) react rm -rf dist

.PHONY: init-php
init-php: build-php composer-install up-php ## Initialize PHP container

.PHONY: build-php
build-php: ## Build PHP container
	$(DOCKER_COMPOSE) build --no-cache php

.PHONY: up-php
up-php: ## Start PHP container
	$(DOCKER_COMPOSE) up -d php

.PHONY: composer-install
composer-install: ## Install Composer dependencies in PHP container
	$(DOCKER_RUN) php composer install

.PHONY: clean-php
clean-php: ## Clean PHP container
	$(DOCKER_RUN) php rm -rf vendor/
	$(DOCKER_RUN) php rm -rf var/cache/*

.PHONY: clean-stack
clean-stack: clean-react clean-php ## Clean all containers

.PHONY: down
down: ## Stop and remove all containers
	$(DOCKER_COMPOSE) down --remove-orphans --volumes

.PHONY: logs
logs: ## Show logs from all containers
	$(DOCKER_COMPOSE) logs -f

.PHONY: logs-database
logs-database: ## Show Database container logs
	$(DOCKER_COMPOSE) logs -f database

.PHONY: logs-php
logs-php: ## Show PHP container logs
	$(DOCKER_COMPOSE) logs -f php

.PHONY: logs-react
logs-react: ## Show React container logs
	$(DOCKER_COMPOSE) logs -f react

.PHONY: logs-caddy
logs-caddy: ## Show Caddy container logs
	$(DOCKER_COMPOSE) logs -f caddy

.PHONY: rebuild
rebuild: ## Rebuild and restart containers without cleaning
	$(DOCKER_COMPOSE) build
	$(DOCKER_COMPOSE) up -d

.PHONY: restart
restart: ## Restart all containers
	$(DOCKER_COMPOSE) down && $(DOCKER_COMPOSE) up -d

.PHONY: prune
prune: ## Clean up unused Docker resources
	docker system prune -f

.PHONY: ps
ps: ## Show running containers
	$(DOCKER_COMPOSE) ps

.PHONY: shell-php
shell-php: ## Open a shell in the PHP container
	$(DOCKER_RUN) php sh

.PHONY: shell-react
shell-react: ## Open a shell in the React container
	$(DOCKER_RUN) react sh

.PHONY: hard-reset
hard-reset: clean-stack down prune setup ## Hard reset the stack
	@echo "Hard reset completed. All containers stopped and removed, volumes cleaned, and stack reinitialized."
	@echo "Run 'make up' to start the stack again."

.PHONY: init-env
init-env: ## Initialize environment files
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

.PHONY: composer-require
composer-require: ## Add a Composer package
	@read -p "Enter the package name to add: " package; \
	if [ -z "$$package" ]; then \
		echo "Package name cannot be empty"; \
		exit 1; \
	fi; \
	$(DOCKER_RUN) php composer require "$$package"; \
	echo "Package '$$package' added successfully"; \
	$(DOCKER_RUN) php composer update; \

.PHONY: pnpm-add
pnpm-add: ## Add a pnpm package
	@read -p "Enter the package name to add: " package; \
	if [ -z "$$package" ]; then \
		echo "Package name cannot be empty"; \
		exit 1; \
	fi; \
	$(DOCKER_RUN) react pnpm add "$$package"; \
	echo "Package '$$package' added successfully"; \