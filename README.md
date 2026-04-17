// file: README.md
# NextGen Assets Management System

A Laravel + React asset management system for tracking assets, assignments, departments, inventory, suppliers, users, notifications, settings, and profile images.

## Tech Stack

- Laravel
- React
- Vite
- MySQL
- Tailwind CSS
- XAMPP
- VS Code

## Requirements

Install these first:

- XAMPP
- PHP 8.2+
- Composer
- Node.js 18+
- npm
- Git
- VS Code

## Clone the Project

```bash

git clone https://github.com/austinkalisik/Nextgen_Assets_Management_System.git
cd Nextgen_Assets_Management_System

4. Start XAMPP

Open XAMPP Control Panel.

Start these services:

Apache
MySQL

Make sure both are running before continuing.

5. Create the database

In XAMPP, click Admin next to MySQL to open phpMyAdmin.

Create a new database called:

CREATE DATABASE nextgen_assets;
6. Install project dependencies

Back in the VS Code terminal, run:

composer install
npm install

This installs all backend and frontend dependencies.

7. Create the environment file

Run:

copy .env.example .env

This creates your local Laravel environment file.

8. Generate the application key

Run:

php artisan key:generate
9. Update the .env file

Open the .env file in VS Code and make sure it contains these values:

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

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

FILESYSTEM_DISK=public
CACHE_STORE=file
QUEUE_CONNECTION=database

Notes:

If your MySQL password in XAMPP is empty, leave DB_PASSWORD= blank.
If your MySQL uses a password, enter it there.
10. Run database migrations and seed data

Run:

php artisan migrate --seed

This will:

create all required database tables
insert the default demo data
create the default admin login
11. Create the storage link

Run:

php artisan storage:link

This is required for profile images and uploaded files.

12. Clear cached config and routes

Run:

php artisan optimize:clear

This helps avoid old cached config issues on a fresh clone.

13. Start the application

You need 2 terminals open in VS Code.

Terminal 1: Start Laravel
php artisan serve

Laravel will start on:

http://127.0.0.1:8000
Terminal 2: Start Vite
npm run dev
14. Open the app

Open this URL in your browser:

http://127.0.0.1:8000/login

Important:

Open /login first
Do not open /dashboard first on a new clone before logging in

After login, you can access:

http://127.0.0.1:8000/dashboard
15. Default login account

Use this demo account:

Email: admin@nextgen.local
Password: password

After successful login, you will be redirected to the dashboard.

16. Full quick setup commands

Run these one by one in VS Code terminal:

git clone https://github.com/austinkalisik/Nextgen_Assets_Management_System.git
cd Nextgen_Assets_Management_System
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan optimize:clear
php artisan serve

Then open a second terminal and run:

npm run dev

Then open:

http://127.0.0.1:8000/login
17. Recommended first test after cloning

After login, test these pages:

Dashboard
Assets
Assignments
Inventory
Suppliers
Categories
Departments
Users
Settings
Profile

Also test:

create a record
edit a record
delete a record
upload profile image
logout and login again
18. If the dashboard does not load

If the app opens but the dashboard or other pages fail, check these first:

Make sure both servers are running

Terminal 1:

php artisan serve

Terminal 2:

npm run dev
Make sure .env is correct

Check:

APP_URL=http://127.0.0.1:8000
correct MySQL database name
SESSION_DRIVER=file
Clear caches again
php artisan optimize:clear
Rerun migrations and seeders if needed
php artisan migrate:fresh --seed
Make sure MySQL is running in XAMPP

If MySQL is off, Laravel cannot load data.

19. Common issues and fixes
Problem: database connection error

Check:

XAMPP MySQL is started
database exists
.env credentials are correct
Problem: login does not work

Make sure you ran:

php artisan migrate --seed

Then use:

admin@nextgen.local
password
Problem: profile images do not show

Run:

php artisan storage:link
Problem: old errors still showing

Run:

php artisan optimize:clear
Problem: frontend is not loading correctly

Run:

npm install
npm run dev
Problem: tables/data are broken

Run:

php artisan migrate:fresh --seed
20. Important testing note

Before pushing changes to GitHub, it is best to test the project in a fresh cloned folder to make sure another developer can run it without hidden local fixes.

21. Project purpose

This project is mainly for:

testing
learning
demo use
local development
22. License

This project is for educational, demo, and testing use.