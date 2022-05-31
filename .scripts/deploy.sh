#!/bin/bash
set -e

echo "Deployment started ..."

# Enter maintenance mode or return true
# if already is in maintenance mode
(php artisan down) || true

# Create env file and add github secrets
touch .env          
echo TMDB_API_KEY=$TMDB_API_KEY > .env

# Pull the latest version of the app (try master instead of production)
git pull origin master

# Install composer dependencies
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Clear the old cache
php artisan clear-compiled

# Recreate cache
php artisan optimize

# Remove node_modules (this solves NPM being a moody bitch sometimes)
rm -rf node_modules

# Install NPM with dependencies
npm install

# Compile npm assets
npm run prod

# Run database migrations
php artisan migrate --force

# Exit maintenance mode
php artisan up

echo "Deployment finished!"
