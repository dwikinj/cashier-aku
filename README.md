# Cashier Aku
A point of sale application built with Laravel, jQuery, and MySQL.

## Features
- [x] CRUD Member
- [x] CRUD Product
- [x] CRUD Category
- [x] Print Invoice
- [ ] CRUD Supplier
- [ ] Sales Dashboard Graph
- [ ] Track Selling
- [x] Inventory Management
- [ ] Track Buying
- [ ] Report Sale
- [ ] User Management (Admin and Cashier roles to distinguish root access)

## How to Install
1. Clone the project
2. Navigate to the project's root directory using terminal
3. Create `.env` file - `cp .env.example .env`
4. Execute `composer install`
5. Set application key - `php artisan key:generate --ansi`
6. Execute migrations and seed data - `php artisan migrate --seed`
7. Start Artisan server - `php artisan serve`

## Demo (Click Thumbnails)
[![Watch the video](https://jam.dev/cdn-cgi/image/width=1000,quality=100,dpr=1.25/https://cdn-jam-screenshots.jam.dev/565c12278004079504dd95c65ee4f320/screenshot/7fcbd665-c71a-40b2-b87c-96a88123cd84.png)](https://youtu.be/G7x0Wxr9qXA)

## Description
Cashier Aku is a comprehensive point of sale application designed to manage various aspects of sales and inventory. It includes features such as CRUD operations for members, products, categories, and suppliers, as well as the ability to print invoices. The application also supports user management with distinct roles for admin and cashier to control access levels.
