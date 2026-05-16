.PHONY: install up down restart migrate fresh seed dev setup

install:
	composer install
	npm install
	cp .env.example .env
	php artisan key:generate

up:
	docker compose up -d

down:
	docker compose down

restart:
	docker compose restart

migrate:
	php artisan migrate

fresh:
	php artisan migrate:fresh

seed:
	php artisan db:seed

dev:
	composer run dev

setup: install up
	sleep 5 # Wait for DB to be ready
	php artisan migrate:fresh
	@echo "Setup complete! Run 'make dev' to start development server."
