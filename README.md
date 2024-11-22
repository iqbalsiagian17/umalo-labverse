
# Umalo - Labverse

Umalo-Labverse is a Laravel-based project built with Laravel 10 and PHP 8.2. This project includes database migrations, seeders, and is organized with a dedicated directory for the Laravel source code.



## Project Details

 - Laravel 10.0
 - Programming Language: PHP 8.2
 - Directory Structure: source_code: Contains all Laravel source code.
 - Database: MySQL / MariaDB


## Installation

1. Clone the Repository

```bash
git clone https://github.com/username/umalo-labverse.git
cd umalo-labverse/source_code
```


2. Install Dependencies

```bash
composer install
```


3. Copy the .env File Copy the example environment file to create your .env file:

```bash
cp .env.example .env
```


4. Configure Environment Variables Update the following database settings in the .env file:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=root
DB_USERNAME=
DB_PASSWORD=db_labverse
```


6. Run Migrations and Seeders Migrate the database and seed it with initial data:

```bash
php artisan migrate --seed
```

7. Start the Development Server Launch the Laravel development server:

```bash
php artisan serve
```





## Key Features

1. Database Migrations: Manage database structure with Laravel Migrations.
2. Database Seeders: Populate the database with initial data using Laravel Seeders.
3. Powered by Laravel 10: Leverage the latest features of Laravel.

