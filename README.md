Umalo-Labverse
Umalo-Labverse is a Laravel-based project built with Laravel 10 and PHP 8.2. This project includes database migrations, seeders, and is organized with a dedicated directory for the Laravel source code.

Project Details
Framework: Laravel 10.0
Programming Language: PHP 8.2
Directory Structure:
                    source_code: Contains all Laravel source code.

Prerequisites
Ensure your system meets the following requirements before running the project:
PHP 8.2 or newer
Composer (latest version)
Node.js (optional, for Laravel Mix or Vite-based frontend)
Database: MySQL / MariaDB

Installation
Follow these steps to set up and run the project locally:

1. Clone the Repository
git clone https://github.com/username/umalo-labverse.git
cd umalo-labverse/source_code

2. Install Dependencies
composer install

3. Copy the .env File Copy the example environment file to create your .env file:
cp .env.example .env

4. Configure Environment Variables Update the following database settings in the .env file:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password

5. Generate Application Key Run the following command to generate the application key:
php artisan key:generate

6. Run Migrations and Seeders Migrate the database and seed it with initial data:
php artisan migrate --seed

7. Start the Development Server Launch the Laravel development server:
php artisan serve

The project will be available at: http://127.0.0.1:8000



Project Structure
umalo-labverse/
│
├── source_code/
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   │   ├── factories/
│   │   ├── migrations/
│   │   └── seeders/
│   ├── public/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── tests/
│   └── ...
├── README.md
└── .gitignore
source_code: Contains all Laravel source code.


Key Features
1. Database Migrations: Manage database structure with Laravel Migrations.
2. Database Seeders: Populate the database with initial data using Laravel Seeders.
3. Powered by Laravel 10: Leverage the latest features of Laravel.



