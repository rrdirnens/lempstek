# !!! **NOTE: this project is a WIP. This README is not complete.** !!!

<br>
<br>

# Entertainment Calendar 

<br>


## Local Docker setup (not 100% complete)

### Dependencies
  Docker<br>
  TMDB API key ([how-to|https://kb.synology.com/en-global/DSM/tutorial/How_to_apply_for_a_personal_API_key_to_get_video_info])
  
<br>

### Helpful links
  https://www.digitalocean.com/community/tutorials/how-to-install-and-set-up-laravel-with-docker-compose-on-ubuntu-22-04 
  
<br>

### Copy and edit the .env file (add TMDB api key and DB credentials). 

```
cp .env.example .env
```

<br>

### On first time setup (with docker):

```
docker-compose build app
```

<br>

### To run the docker container

```
docker-compose up -d
```

to run in background. Check docker-compose commands for more info: https://docs.docker.com/compose/reference/ 

[optional] 
```docker-compose ps```
to see state of active services (containers running in background)

<br>

### Composer install (+ notice, this is how you run composer commands)

```
docker-compose exec app rm -rf vendor composer.lock
docker-compose exec app composer install
```

<br>

### Generate the APP_KEY (+ notice, this is how you run artisan commands)

```
docker-compose exec app php artisan key:generate
```

<br>

### Access the project in yo browsah

http://server_domain_or_IP:8000, i.e. probably `localhost:8000`

<br>

### NODE/NPM setup

Follow this very short and simple instructional article to install Node and NPM https://logfetch.com/install-node-npm-wsl2/ 

Once done, to install the packages defined in package.json, run: 

```
npm install
```

To watch assets for development run this. BrowserSync enabled by default and runs on port 3000, so if you want hot reloads, go to :3000 not :8000. The port can be changed in webpack.mix.js file in project root.

```
npm run watch
```

To build assets for deployment, run:
```
npm run prod
``` 

