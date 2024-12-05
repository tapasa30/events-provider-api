DOCKER_COMPOSE := docker-compose -p events_api -f docker-compose.yml

.DEFAULT_GOAL = help

##
## —— Providers API ——————————————————————————————————
##
help: ## Prints this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/' ##

build: ## Builds docker containers
	@$(DOCKER_COMPOSE) build --no-cache

up: ## Turns up docker containers
	@$(DOCKER_COMPOSE) up -d

down: ## Turns down docker containers
	@$(DOCKER_COMPOSE) down

run:  ## Builds and initializes the project
	make build
	make up
	@$(DOCKER_COMPOSE) exec --user=$(shell id -u) php composer install
	@$(DOCKER_COMPOSE) exec --user=$(shell id -u) php php bin/console doctrine:database:create
	@$(DOCKER_COMPOSE) exec --user=$(shell id -u) php php bin/console doctrine:schema:update -f

synchronize_provider_events: ## Runs Event Providers Synchronization Command
	@$(DOCKER_COMPOSE) exec --user=$(shell id -u) php php bin/console app:event-provider:synchronize