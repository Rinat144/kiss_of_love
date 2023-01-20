ЗАПУСК DOCKER CONTAINER, SEEDER, СЕРВЕРА:
npm install
composer install
docker compose up -d
php artisan db:seed --class=CitySeeder
php artisan serve
СГЕНЕРИРОВАТЬ JWT TOKEN
php artisan jwt:secret


