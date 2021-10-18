# Simple Blog App
This app was build using laravel, laravel sanctum and vue.
Only registered and logged in users can create posts and write comments under them.
Each post and resource can be destroyed only by his creator.
Posts and comments are paginated.
Posts and comments can be read by guests.
Each endpoint was tested with PHPUnit.
## Table of contents

1. [Technologies](#Requirements)
2. [Setup](#Setup)
3. [Manual Testing](#Manual-Testing)
4. [Unit Tests](#Unit-Tests)
5. [Licence](#Licence)

## Technologies
Technologies used during development of Poll App:
* PHP 8.0.11
* Composer
* Laravel 8.x
* Laravel Sanctum 2.11
* PHPUnit 9.3.3
* MariaDB 10.4.21
* Node.JS 14.17.1
* NPM 6.14.13
* Vue 3.0
* Bootstrap 4.6

## Setup
Download the app using git:
```
$ git clone https://github.com/michalsamsel/simple-blog-app.git
$ cd ./simple-blog-app
```

To enable app:
```
$ composer install
$ cp .env.example .env
$ php artisan key:generate
$ php artisan migrate
$ php artisan serve

$ npm install
$ npm run prod
```

## Manual Testing
To test app database should be seeded with resources.
After setup database can be seeded using command:
```
$ php artisan db:seed
```
For testing there is predefined account in seeder:

**Login**: example@email.com  
**Password**: password

Or any other account created by seeder but login and password needs to be checked in database.

## Unit Tests
To run unit test there is a command:
```
$ php artisan test
```
All 43 tests should pass successfully.
## Licence
[The MIT License](https://opensource.org/licenses/MIT)