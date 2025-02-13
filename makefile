.PHONY: build start stop restart database migrate

build:
	docker-compose build

start: build
	docker compose up --detach
	npm run build-css
	npm run build-js
	@echo "Waiting for services to start..."
	@make migrate

migrate:
	docker compose up migration

stop:
	docker-compose down --remove-orphans --volumes --timeout 0

database:
	docker compose exec mariadb sh -c 'mysql --user=$$DATABASE_USER --password=$$DATABASE_PASSWORD $$DATABASE_NAME'

restart: stop start
