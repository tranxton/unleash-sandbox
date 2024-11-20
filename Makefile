DOCKER_EXEC_APP=docker-compose exec app

.app.env.init:
	@echo "Initializing .env.local..."
	@$(DOCKER_EXEC_APP) sh -c "if ! ls .env.local >/dev/null 2>&1; then cp .env .env.local; fi"
	@echo ".env.local initialized."

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

init: \
	docker.up \
	.app.env.init \
	.app.env.secret.generate \
	app.composer.install \
	.app.database.create \
	app.database.migrate


app.composer.install:
	@echo "Installing composer dependencies..."
	@$(DOCKER_EXEC_APP) composer install --audit --no-autoloader
	@$(DOCKER_EXEC_APP) composer dump-autoload --optimize --classmap-authoritative --strict-psr
	@echo "Composer dependencies installed."

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
