# Entertainment Calendar 

## Local Docker setup (not 100% complete)

### Dependencies
  Docker
  
### Helpful links
  https://www.digitalocean.com/community/tutorials/how-to-install-and-set-up-laravel-with-docker-compose-on-ubuntu-22-04 

### Copy and edit the .env file (add TMDB api key and DB credentials). 

`cp .env.example .env`

### On first time setup (with docker):

`docker-compose build app`

### To run the docker container

`docker-compose up -d` to run in background. Check docker-compose commands for more info: https://docs.docker.com/compose/reference/ 

[optional] `docker-compose ps` to see state of active services (containers running in background)

### Composer install (+ notice, this is how you run composer commands)

`docker-compose exec app rm -rf vendor composer.lock`
`docker-compose exec app composer install`

### Generate the APP_KEY (+ notice, this is how you run artisan commands)

`docker-compose exec app php artisan key:generate`

### Access the projects in yo browsah

http://server_domain_or_IP:8000, i.e. probably `localhost:8000`


[=== TODO ===]

`npm run watch` to watch assets for development (browserSync enabled by default and runs on port 3000)
