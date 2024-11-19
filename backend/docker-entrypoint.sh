#!/bin/bash

# Installing dependencies
composer install

# Apply database migrations
php bin/console doctrine:migrations:migrate

# Creating a directory for uploaded files
sudo mkdir -p /app/public/upload/
sudo chown -R www-data:www-data /app/public/upload/

# Waiting for database availability
/wait-for-it.sh -t 0 db:3306

# Start server
exec php-fpm