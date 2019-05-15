MENEAME TEST
=====================


# Installation 

### Requirements

- PHP 7.1
- Docker Compose
- Composer
- Bower
- NPM

Clone this repository using SSH

```bash
git clone ssh://git@vps161376.ovh.net:8022/mmartinez/test-meneame.git
```

Install the backend dependencies using composer

```bash
composer install
```

If composer install fails, execute in php7 docker container (after docker-compose run)

```bash
docker-compose exec php7 bash
composer install
```


# Run

### Using Docker Compose

Use Docker Compose to build this application.

```bash
docker-compose up --build -d
```

# Rss commands
Console commands have been created to manage the rss news.

```
php bin/console meneame:list

```

# Test

To run the tests just access the path of project and run:

```bash
php vendor/bin/simple-phpunit --bootstrap vendor/autoload.php tests
```

