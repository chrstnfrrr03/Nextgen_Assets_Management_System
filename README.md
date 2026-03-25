# NextGen Assets Inventory System

A modern **Sales & Inventory Management System** built with Laravel.

This system helps manage:

- Assets / Inventory
- Suppliers
- Users
- Categories
- Reports

---

## Features

### Authentication

- Login / Register
- Secure password hashing
- Session management

### Dashboard

- Total Assets
- Total Brands
- Recently Added Items
- System Summary

### Inventory (Assets)

- Add, Edit, Delete Assets
- Track part number, brand, name, description

### Suppliers

- Manage suppliers
- Add / Delete suppliers

### Users

- User management
- Edit & delete users

### Categories

- Organize inventory
- Assign categories to items

### Reports

- System analytics overview

---

## Tech Stack

- Laravel (PHP Framework)
- MySQL (Database)
- Blade (Frontend)
- Tailwind CSS (UI)

## Installation

```bash
git clone https://github.com/austinkalisik/nextgen-assets.git
cd backend

composer install

cp .env.example .env

php artisan key:generate
