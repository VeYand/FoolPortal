#!/bin/bash

# Installing dependencies
composer install

# Waiting for database availability
./wait-for-it.sh -t 0 db:3306

# Apply database migrations
symfony console doctrine:migrations:migrate

# Start server
exec php-fpm