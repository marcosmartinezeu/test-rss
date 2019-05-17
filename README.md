MENEAME TEST
=====================


# Installation 

### Requirements

- PHP 7.1
- Docker Compose
- Composer
- GIT

Clone this repository using SSH

```bash
git clone https://github.com/marcosmartinezeu/test-rss
```

# Run

### Using Docker Compose

Use Docker Compose to build this application.

```bash
docker-compose up --build -d
```

Install the backend dependencies using composer

```bash
composer install
```

**NOTE:** If composer install fails, execute in php docker container (after docker-compose run)

```bash
docker-compose exec php sh
composer install
```


# Rss commands
Console commands have been created to manage the rss news.

***meneame:main-list***

```
php bin/console meneame:main-list 
 
Description:
  Meneame Main List

Usage:
  meneame:main-list [options]

Options:
  -m, --max[=MAX]       Max results [20]
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -e, --env=ENV         The Environment name. [default: "dev"]
      --no-debug        Switches off debug mode.
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

```

***meneame:queued-list***

```
php bin/console meneame:queued-list
 
Description:
  Meneame Queued List

Usage:
  meneame:queued-list [options]

Options:
  -m, --max[=MAX]       Max results [20]
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -e, --env=ENV         The Environment name. [default: "dev"]
      --no-debug        Switches off debug mode.
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

```

***meneame:update-rss***

```
php bin/console meneame:update-rss
 

Description:
  Update database from rss data

Usage:
  meneame:update-rss [options]

Options:
  -s, --status[=STATUS]  Status (published or queued)
  -h, --help             Display this help message
  -q, --quiet            Do not output any message
  -V, --version          Display this application version
      --ansi             Force ANSI output
      --no-ansi          Disable ANSI output
  -n, --no-interaction   Do not ask any interactive question
  -e, --env=ENV          The Environment name. [default: "dev"]
      --no-debug         Switches off debug mode.
  -v|vv|vvv, --verbose   Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```


# Crontasks

To get rss information from API and storage a cron task is installed in PHP container executed every minute. To get this information, this task loads ***meneame:update-rss*** cli command.          
        
```
*/1 * * * * /usr/bin/php /var/www/symfony/bin/console meneame:update-rss
*/1 * * * * /usr/bin/php /var/www/symfony/bin/console meneame:update-rss --status queued/
```
# Test

To run the tests just access the path of project and run:

```bash
php vendor/bin/simple-phpunit --bootstrap vendor/autoload.php tests
```

