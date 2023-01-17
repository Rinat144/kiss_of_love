ЗАПУСК DOCKER CONTAINER, SEEDER, СЕРВЕРА:
docker compose up -d
php artisan db:seed --class=CitySeeder
php artisan serve

