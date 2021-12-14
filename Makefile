install:
	cp .env-local .env
	docker-compose up -d --build
	docker exec -it app bash -c 'composer install'
	docker exec -it app bash -c 'npm install'
	docker exec -it app bash -c 'composer dump-autoload'
	docker exec -it app bash -c 'php artisan migrate'
	docker exec -it app bash -c 'php artisan db:seed --class=AdminUser'

up:
	docker-compose up -d --build

down:
	docker-compose down

update:
	docker exec -it app bash -c 'composer install'
	docker exec -it app bash -c 'npm install'
	docker exec -it app bash -c 'php artisan migrate'

watch:
	docker exec -it app bash -c 'npm run watch'

console:
	docker exec -it app bash
