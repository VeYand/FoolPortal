#!/bin/bash

# Installing dependencies
composer install

# Creating a directory for uploaded files
sudo mkdir -p /app/public/upload/
sudo chown -R www-data:www-data /app/public/upload/

# Waiting for database availability
/wait-for-it.sh -t 0 db:3306

# Apply database migrations
php bin/console doctrine:migrations:migrate

# Start server
exec php-fpm