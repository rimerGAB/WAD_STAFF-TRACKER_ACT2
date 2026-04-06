# Staff Tracker System

![PHP 8.4+](https://img.shields.io/badge/PHP-8.4%2B-777BB4)
![Laravel 13](https://img.shields.io/badge/Laravel-13-FF2D20)
![React 19](https://img.shields.io/badge/React-19-61DAFB)
![SQLite](https://img.shields.io/badge/SQLite-003B57)

> **⚠️ Requirements:** PHP 8.4 or higher is required. PHP 8.3 is NOT supported.

## Screenshot

![Employee List Page](screenshot.png)

*Employee list showing department, profile, and project relationships*

## Database Relationships

| Relationship | Type | Tables |
|--------------|------|--------|
| One-to-Many | Department → Employee | One department has many employees |
| One-to-One | Employee → Profile | One employee has one profile |
| Many-to-Many | Employee ↔ Project | Many employees work on many projects (via Assignment pivot) |

## ER Diagram

![ER Diagram](erd.jpg)

## Tables

| Table | Primary Key | Foreign Keys |
|-------|-------------|--------------|
| departments | dept_id | - |
| employees | emp_id | dept_id |
| profiles | prof_id | emp_id (UNIQUE) |
| projects | proj_id | - |
| assignments | Assign_id | emp_id, proj_id |

## Tech Stack

- **Backend:** Laravel 13
- **Frontend:** React 19 + Inertia.js
- **Database:** SQLite
- **Styling:** Tailwind CSS

## Installation

```bash
# Clone repository
git clone https://github.com/rimerGAB/WAD_STAFF-TRACKER_ACT2.git
cd WAD_STAFF-TRACKER_ACT2

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Create database (Windows)
type nul > database\database.sqlite

# Run migrations and seeders
php artisan migrate
php artisan db:seed

# Start servers
php artisan serve
npm run dev
```
----------------------------------
ERD:
![Employee List](screenshot/erd.jpg)
Author
rimerGAB

License
MIT
