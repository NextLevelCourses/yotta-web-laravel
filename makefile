dy-start:
	docker compose -f docker-compose.yml up -d
#stop docker haircut
dy-stop:
	docker compose -f docker-compose.yml down
#list docker haircut
dy-ps:
	docker compose -f docker-compose.yml ps
#list docker images
dy-image:
	docker image ls
#restart docker haircut
dy-restart:
	docker compose -f docker-compose.yml restart
#logs docker haircut
dy-logs:
	docker compose -f docker-compose.yml logs -f
#build docker haircut
dy-build:
	docker compose -f docker-compose.yml build dashboard

#================= Docker Command Laravel and PHP ================
dy-migrate:
	docker compose -f docker-compose.yml exec dashboard php artisan migrate
	docker compose -f docker-compose.yml exec dashboard php artisan db:seed
dy-rollback:
	docker compose -f docker-compose.yml exec dashboard php artisan migrate:rollback
#exec run migrate refresh
dy-refresh:
	docker compose -f docker-compose.yml exec dashboard php artisan migrate:refresh
	docker compose -f docker-compose.yml exec dashboard php artisan db:seed
#exec run seeder
dy-seed:
	docker compose -f docker-compose.yml exec dashboard php artisan db:seed
#exec app docker via composer install
dy-composer-install:
	docker compose -f docker-compose.yml exec dashboard composer install
dy-composer-update:
	docker compose -f docker-compose.yml exec dashboard composer update
dy-php-m:
	docker compose -f docker-compose.yml exec dashboard php -m
dy-dir-project:
	docker exec -it yotta-dashboard bash
dy-laravel-optimize-all:
	docker compose -f docker-compose.yml exec dashboard php artisan optimize
	docker compose -f docker-compose.yml exec dashboard php artisan cache:clear
	docker compose -f docker-compose.yml exec dashboard php artisan config:clear
	docker compose -f docker-compose.yml exec dashboard php artisan route:clear
	docker compose -f docker-compose.yml exec dashboard php artisan view:clear

y-start:
	php artisan serve
y-migrate:
	php artisan migrate
y-rollback:
	php artisan migrate:rollback
y-refresh:
	php artisan migrate:refresh
y-seed:
	php artisan db:seed