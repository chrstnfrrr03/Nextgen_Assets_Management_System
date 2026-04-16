# NextGen Assets Management System

Laravel + React asset management system for tracking assets, assignments, departments, inventory, suppliers, users, settings, and notifications.

## Features

- Laravel backend
- React SPA frontend
- Authentication
- Dashboard
- Assets management
- Assignments tracking
- Departments, categories, suppliers
- Settings and profile image upload

## Tech Stack

- PHP 8.3+
- Laravel 12
- React
- Vite
- MySQL
- Tailwind CSS

## Requirements

Make sure you have installed:

- PHP 8.2 or newer
- Composer
- Node.js 18 or newer
- npm
- MySQL
- Git
- VS Code

## Clone the Project

```bash
git clone https://github.com/austinkalisik/Nextgen_Assets_Management_System.git

cd Nextgen_Assets_Management_System

## (Open in VS Code)

-code .
-Install Dependencies
-composer install
-npm install
-Environment Setup

Copy the environment file:

copy .env.example .env

## If you are using Git Bash or WSL:

cp .env.example .env

## Generate the Laravel app key:

php artisan key:generate

## Database Setup

##Create a MySQL database, for example:

CREATE DATABASE nextgen_assets;

Then update .env:

APP_NAME="NextGen Assets"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nextgen_assets
DB_USERNAME=root
DB_PASSWORD=

## In Terminal Run
-Run Migrations and Seeders
-php artisan migrate --seed
-Storage Link
-php artisan storage:link
## Start the Application

## Run Laravel backend:

php artisan serve

Run Vite frontend in a second terminal:

npm run dev

Open:

http://127.0.0.1:8000
Demo Login
Email: admin@nextgen.local
Password: password

## Common Fixes
Clear caches
php artisan optimize:clear
Rebuild frontend
npm install
npm run dev
Reset database
php artisan migrate:fresh --seed


## Project Structure
app/
bootstrap/
config/
database/
public/
resources/
routes/
storage/
Notes
##Make sure MySQL(XAMPP) is running before migration
Do not commit .env
Do not commit vendor or node_modules
Use php artisan storage:link if profile images do not show
License

This project is for testing and educational/demo use.