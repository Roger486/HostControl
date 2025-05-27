# 📦 Full Stack Deployment Guide (Angular + Laravel)

This document details how to deploy a project locally with a frontend in Angular and a backend in Laravel.

---

## ✅ Prerequisites

Make sure you have installed:

- Node.js (v18 or higher)
- Git
- Angular CLI:
  ```bash
  npm install -g @angular/cli
  ```
- Composer
- MySQL (e.g., using XAMPP)
- PHP (compatible with Laravel)
- Visual Studio Code (optional but recommended)

---

## 🧩 1. Clone the Repository

Open a terminal in VS Code and run:

```bash
git clone https://github.com/LaSalleFPOnline/proyectofinal-24-25-grupo-13.git
```

---

## 🌐 2. Frontend Deployment (Angular)

### a. Navigate to the frontend directory

```bash
cd proyectofinal-24-25-grupo-13/frontend
```

### b. Install dependencies

```bash
npm install
```

### c. Verify `angular.json` (optional)

Ensure the file has the required configuration.

### d. Launch the development server

```bash
ng serve
```

### e. Open in browser

```url
http://localhost:4200/
```

---

## 🛠️ 3. Backend Deployment (Laravel)

### a. Navigate to the backend directory

```bash
cd proyectofinal-24-25-grupo-13/backend
```

### b. Install dependencies

```bash
composer install
```

### c. Create `.env` file and generate app key

```bash
copy .env.example .env
php artisan key:generate
```

### d. Configure the database

Ensure MySQL is running (e.g., using XAMPP) and update `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=project_db
DB_USERNAME=root
DB_PASSWORD=
SESSION_DRIVER=file
```

Create the database if it doesn't exist:

```bash
mysql -u root -p -e "CREATE DATABASE project_db;"
```

### e. Install Sanctum and create symbolic links

```bash
composer require laravel/sanctum
php artisan storage:link
```

### f. Clear cache and reset the database

```bash
php artisan config:clear
php artisan migrate:fresh --seed
```

> This will delete all tables, recreate them, and run the seeders.

### g. Start the Laravel server

```bash
php artisan serve
```

> If port 8000 is busy, use:
```bash
php artisan serve --port=8080
```

Access from: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 🧪 4. API Testing (optional)

If you're using Laravel as an API, test with:

```bash
curl http://127.0.0.1:8000/api/route
```

Or use Postman to test endpoints, tokens, etc.

---

## ✅ Done!

Your Angular + Laravel development environment is running locally. 🚀
