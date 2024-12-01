DOCKER_EXEC_APP=docker-compose exec app

.app.env.init:
	@echo "Initializing .env.local..."
	@$(DOCKER_EXEC_APP) sh -c "if ! ls .env.local >/dev/null 2>&1; then cp .env .env.local; fi"
	@echo ".env.local initialized."

.app.env.set-prod:
	@echo "Setting application environment to prod..."
	@$(DOCKER_EXEC_APP) sed -i -e 's/APP_ENV=dev/APP_ENV=prod/' -e 's/APP_DEBUG=1/APP_DEBUG=0/' .env.local
	@echo "Application environment set to prod."

.app.env.set-dev:
	@echo "Setting application environment to dev..."
	@$(DOCKER_EXEC_APP) sed -i -e 's/APP_ENV=prod/APP_ENV=dev/' -e 's/APP_DEBUG=0/APP_DEBUG=1/' .env.local
	@echo "Application environment set to dev."

.app.env.secret.generate:
	@echo "Generating APP_SECRET..."
	@$(DOCKER_EXEC_APP) sh -c 'if grep -q "^APP_SECRET=" .env.local; then sed -i "s/^APP_SECRET=.*/APP_SECRET=$$(openssl rand -hex 32)/" .env.local; fi'
	@echo "APP_SECRET generated."

.app.database.create:
	@echo "Creating database..."
	@$(DOCKER_EXEC_APP) bin/console doctrine:database:create --if-not-exists
	@echo "Database created."

.docker.app.wait_for_start:
	@until [ "$$(docker-compose ps -q --status=running app)" ]; do echo "Waiting for app to start..."; sleep 1; done

.app.composer.first-install:
	@echo "Installing composer dependencies..."
	@docker run --rm -it -v .:/app -w /app composer:2 composer first-install
	@echo "Composer dependencies installed."

.app.composer.check:
	@echo "Installing validating & auditing composer.json..."
	@$(DOCKER_EXEC_APP) composer validate && composer audit
	@echo "composer.json is valid."

init: \
	.app.composer.first-install \
	docker.up \
	.app.env.init \
	.app.env.secret.generate \
	app.cache.clear \
	.app.composer.check \
	.app.database.create \
	app.database.migrate

app.build.prod: \
	.app.env.set-prod \
	app.composer.install-prod \
	app.composer.dump-prod-env \
	app.cache.clear \
	docker.app.restart

app.build.dev: \
	.app.env.set-dev \
	app.composer.install-dev \
	app.composer.remove-prod-env \
	app.cache.clear \
	docker.app.restart

app.cache.clear:
	@echo "Clearing the cache..."
	@$(DOCKER_EXEC_APP) bin/console cache:clear
	@echo "Cache was successfully cleared."

app.composer.dump-prod-env:
	@echo "Dumping prod .env..."
	@$(DOCKER_EXEC_APP) composer dump-env prod
	@echo "Successfully dumped prod .env file."

app.composer.remove-prod-env:
	@echo "Removing prod .env.local.php dump..."
	@$(DOCKER_EXEC_APP) rm -f .env.local.php
	@echo "Removed .env.local.php dump."

app.composer.install-prod:
	@echo "Installing dev dependencies..."
	@$(DOCKER_EXEC_APP) composer install-prod
	@echo "Prod dependencies installed."

app.composer.install-dev:
	@echo "Installing dev dependencies..."
	@$(DOCKER_EXEC_APP) composer install-dev
	@echo "Dev dependencies installed."

app.database.migrate:
	@echo "Running migrations..."
	@$(DOCKER_EXEC_APP) bin/console doctrine:migrations:migrate -n
	@echo "Migrations ran successfully."

docker.up:
	@echo "Starting docker containers..."
	@docker-compose up -d
	@echo "Docker containers started."

docker.stop:
	@@echo "Stopping docker containers..."
	@@docker-compose stop
	@@echo "Docker containers stopped."

docker.down:
	@echo "Removing docker containers..."
	@docker-compose down
	@echo "Docker containers removed."

docker.app.restart:
	@echo "Restarting app container..."
	@docker-compose restart app
	@echo "App container restarted."
