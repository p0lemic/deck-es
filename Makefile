## —— Make file ——————————————————————————————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'


## —— 🛠️Project tools ———————————————————————————————————————————————————
purge: ## Purge cache and logs
	rm -rf var/logs/*.log
	ls var | grep -v logs | grep -v cache | xargs -I % sh -c 'rm -rf var/%'
	ls var/cache/dev | grep -v '.gitkeep' | xargs -I % sh -c 'rm -rf var/cache/dev/%'
	ls var/cache/test | grep -v '.gitkeep' | xargs -I % sh -c 'rm -rf var/cache/test/%'
	docker exec -it deck-php sh -c "bin/console cache:clear"
	docker exec -it deck-php sh -c "vendor/bin/psalm --clear-cache"
	docker exec -it deck-php sh -c "vendor/bin/ecs check src --clear-cache"

install: ## Install vendors
	docker exec -it deck-php sh -c "composer i"

update: ## Update vendors
	docker exec -it deck-php sh -c "composer u"

dumpa: ## Autoload classes
	docker exec -it deck-php sh -c "composer dumpa"


## —— 🐳 Docker —————————————————————————————————————————————————————————
build:	## Build the project by installing all needed dependencies and start the app once finished
build: build-container start

build-container:
	@docker-compose up --build --force-recreate --no-deps -d

start: ## Start container
	@docker-compose up -d

stop: ## Stop project
	@docker-compose stop

restart: ## Restart project
restart: stop start

destroy: ## Destroy project including volumes
	@docker-compose down -v

shell: ## Interactive shell inside vendimia php container
	@docker exec -it deck-php sh

## —— ✨  Code Quality ———————————————————————————————————————————————————
WITH_INFO=false
psalm: ## Run psalm code analysis
	@docker exec -it deck-php sh -c "vendor/bin/psalm --diff --show-info=$(WITH_INFO)"

metrics: ## Run phpmetrics analysis and generates report
	@docker exec -it deck-php sh -c "vendor/bin/phpmetrics --report-html=var/phpmetrics src"

ecs: ## Run Easy-Code-Standard
	docker exec -it deck-php sh -c "vendor/bin/ecs check src"

ecs-fix: ## Run Easy-Code-Standard Fix
	docker exec -it deck-php sh -c "vendor/bin/ecs check src --fix"

## —— ✅  Testing ————————————————————————————————————————————————————————
test: ## Run all tests
	docker exec -it deck-php sh -c "bin/phpunit --testdox --colors=always"

test-one: ## Run especific test
	docker exec -it deck-php sh -c "bin/phpunit --filter $(test)"

tests-coverage: ## Run unit tests
	docker exec -it deck-php sh -c "bin/phpunit --stop-on-failure --testdox --colors=always --testsuit unit --coverage-html var/phpunit/coverage-report"

tests-integration: ## Run integration tests
	docker exec -it deck-php sh -c "bin/phpunit --stop-on-failure --testdox --colors=always --testsuite integration"

tests-functional: ## Run functional tests
	docker exec -it deck-php sh -c "bin/phpunit --stop-on-failure --testdox --colors=always --testsuite functional"

tests-unit: ## Run unit tests
	docker exec -it deck-php sh -c "bin/phpunit --stop-on-failure --testdox --colors=always --testsuite unit"
