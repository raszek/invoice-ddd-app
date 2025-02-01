#! /bin/bash

cp .env.example .env
touch database/database.sqlite
composer install --ignore-platform-reqs
sudo docker compose up --build --remove-orphans -d
sudo docker compose run app composer install
sudo docker compose run app cp -n .env.example .env
sudo docker compose run app php artisan migrate:fresh --seed
