PHP_CONTAINER = parcel-storage-php-1
DB_CONTAINER = parcel-storage-db-1
NGINX_CONTAINER = parcel-storage-nginx-1

symfony-bash:
	docker exec -it -w /../srv $(PHP_CONTAINER) bash

up:
	docker compose up -d

down:
	docker stop $(PHP_CONTAINER) $(DB_CONTAINER) $(NGINX_CONTAINER)

psql:
	docker exec -it $(DB_CONTAINER) psql -h localhost --port 5432 -U app app
