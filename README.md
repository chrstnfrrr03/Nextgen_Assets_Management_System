# NextGen Asset Management System

A Laravel-based asset management system for tracking, assigning, returning, and monitoring organizational assets in one place.

It is built for internal company use with support for asset records, assignments, inventory movement, departments, suppliers, user roles, notifications, and dashboard reporting.

---

## Features

- Asset management
  - Create, edit, view, and delete assets
  - Track asset tag, serial number, category, supplier, department, quantity, and status

- Assignment management
  - Assign assets to users
  - Return assigned assets
  - Track assignment history and accountability

- Inventory control
  - Stock in / stock out actions
  - Low stock detection
  - Automated status updates

- User management
  - System Administrator, Asset Officer, Manager, and Staff roles
  - User administration
  - Account impersonation / switch account for admin

- Notifications
  - Notification center
  - Header notification dropdown
  - Mark read / mark all read

- Settings
  - System branding
  - System name and tagline preview

- Profile management
  - Update profile details
  - Upload profile photo
  - Change password

- Dashboard
  - Asset statistics
  - Recent assignments
  - Activity logs
  - Notifications summary

---

## Tech Stack

- Laravel
- Blade
- Tailwind CSS
- Alpine.js
- MySQL
- XAMPP
- VS Code

---

## Local Development Environment

This project is being developed with:

- **VS Code**
- **XAMPP**
- **PHP**
- **MySQL**
- **Composer**
- **Node.js / npm**

---

## Project Structure

```bash
backend/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── .env.example
├── artisan
├── composer.json
├── package.json
└── README.md


##  Installation (Step-by-step)

### 1. Clone repository

```bash
git clone https://github.com/austinkalisik/nextgen-assets.git
cd nextgen-assets/backend
```

---

### 2. Install dependencies

```bash
composer install
```

---

### 3. Setup environment

```bash
cp .env.example .env
php artisan key:generate
```

---

### 4. Configure database

Open `.env` and update:

```env
DB_DATABASE=nextgen_assets
DB_USERNAME=root
DB_PASSWORD=
```

---

### 5. Create database

In phpMyAdmin:

```
Create database: nextgen_assets
```

---

### 6. Run migrations + seed

```bash
php artisan migrate:fresh --seed
```

---

### 7. Start server

```bash
php artisan serve
```

---

##  Default Login

```

```
## Default Login Accounts

After running:

php artisan migrate:fresh --seed

Use any of the following accounts:

Admin:
Email: admin@nextgen.local
Password: password

Asset Officer:
Email: assets@nextgen.local
Password: password

ICT Support:
Email: support@nextgen.local
Password: password

Manager:
Email: operations@nextgen.local
Password: password

---

 Note:
If login fails, ensure you have seeded the database:

php artisan migrate:fresh --seed

---

##  Key Modules

* Dashboard → Overview & analytics
* Assets → Manage company assets
* Assignments → Assign & return assets
* Inventory → Stock control
* Suppliers → Vendor management
* Categories → Asset classification
* Departments → Organizational structure
* Users → System users
* Settings → System config

---

##  Notes

* `.env` is not included (create manually)
* Works with XAMPP MySQL
* Designed for corporate asset tracking

---

## Author

Programmer4
