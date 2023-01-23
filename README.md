# Food Ordering App

## Installation

This repo uses docker containers through Laravel Sail to quickly setup the server using the services:
- PHP 8.2.
- MySQL 8.
- Redis.
- Mailhog

If you have docker and docker-compose installed, then installation steps are:
- change directory to the repository root directory.
- copy .env.example to .env
- run `composer install` or using docker.
```
    docker run --rm --interactive --tty \
    --volume $PWD:/app \
    composer install )
```
- run `./vendor/bin/sail up`.
- You can use the .devcontainer config to open vscode in the docker container or simply attach a shell to the php container.
- run `php artisan migrate --seed` to generate the data.

## Usage

- To run the tests, from the php container's shell type `php artisan test`.
- To request the order API using an API platform like Postman use the following request:
```http
   POST http://localhost/orders
   Accept: application/json
   {
        "products": [
            {"product_id" : 1, "quantity": 1}
        ]
   }
```
