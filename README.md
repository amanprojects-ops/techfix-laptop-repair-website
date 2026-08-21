# TechFix — Laptop Repair Management System

## Backend Setup Guide

### Requirements
- PHP 8.1+
- MySQL 8.0+
- Apache with `mod_rewrite` enabled (XAMPP/Laragon/WAMP)
- Composer

---

### Step 1 — Install Dependencies
```bash
composer install
```

### Step 2 — Configure Environment
```bash
cp .env.example .env
```
Edit `.env` with your database credentials:
```
DB_NAME=techfix_repair
DB_USER=root
DB_PASS=your_password
```

### Step 3 — Create Database & Import Schema
Open phpMyAdmin or run:
```bash
mysql -u root -p < database/migrations/001_initial_schema.sql
```

### Step 4 — Set Web Root
Point your web server's document root to the `public/` folder.

For XAMPP: Place the project in `htdocs/` and access via `http://localhost/aman-laptop-reparing/public/`

Or use PHP's built-in server:
```bash
composer start
# Then open http://localhost:8000
```

### Step 5 — Default Admin Login
- **URL**: `http://localhost:8000/admin/login`
- **Email**: `admin@techfix.in`
- **Password**: `admin123`

> ⚠️ Change the password immediately after first login!

---

### Project Structure
```
public/         ← Web root (only this is exposed)
app/            ← PHP application code
  Core/         ← Router, Database, Controller, Session
  Controllers/  ← HTTP request handlers
  Models/       ← Database models
  Services/     ← Business logic
  Middleware/   ← Auth, CSRF, Admin guards
config/         ← App & database config
resources/views ← PHP view templates
routes/         ← web.php + admin.php
database/       ← SQL migration + seed scripts
storage/        ← Logs + uploaded images (not public)
```

### URLs
| URL | Description |
|-----|-------------|
| `/` | Homepage |
| `/book-repair` | Customer booking form |
| `/track-repair` | Repair status tracker |
| `/admin/login` | Admin login |
| `/admin/dashboard` | Admin dashboard |
| `/admin/repairs` | Repair jobs queue |
| `/admin/repairs/create` | New device intake |
| `/admin/customers` | Customer list |
| `/admin/technicians` | Technician management |
| `/admin/services` | Service catalog |
