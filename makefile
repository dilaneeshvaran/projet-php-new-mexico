.PHONY: build start stop restart database migrate

build:
	docker-compose build

start: build
	docker compose up --detach
	@echo "Waiting for services to start..."
	@make migrate
	npm run build-css
	npm run build-js

migrate:
	docker compose --profile migration up migration --abort-on-container-exit --exit-code-from migration

stop:
	docker-compose down --remove-orphans --volumes --timeout 0

database:
	docker compose exec mariadb sh -c 'mysql --user=$$DATABASE_USER --password=$$DATABASE_PASSWORD $$DATABASE_NAME'

restart: stop start
