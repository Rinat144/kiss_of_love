buildapp:
	docker-compose run --rm php php artisan optimize
	docker-compose run --rm php php artisan optimize:clear
	docker-compose run --rm php php artisan jwt:secret
	docker-compose run --rm php php artisan jwt:secret
	make permissions

down:
	docker-compose down

run:
	make down &&\
	make up &&\
	make install

install:
	make buildapp
	make migrations

migrations:
	docker-compose run --rm php php artisan migrate

permissions:
	chmod -R 777 ./storage

restart:
	make down
	make up

up:
	if [ -d /var/run/docker.sock ];then \
	sudo chown ${USER} /var/run/docker.sock ;\
	fi
	if [ -d /run/docker.sock ];then \
	sudo chown ${USER} /run/docker.sock ;\
	fi
	docker-compose up -d

