# Admin Panel Stack

This repository contains a simple full-stack admin panel built with:

* Frontend: React + Vite + Material-UI (in `frontend/`)
* Backend/API: PHP (in `api/`)
* Database: MySQL (schema files in `mysql/`)
* Local containers orchestrated with **Docker Compose** (`docker-compose.yml`)

## Quick Start

1. **Clone & build containers**

```bash
docker-compose up -d --build
```

The command starts three services:

* `event-apache` – Apache + PHP, exposed on **localhost:9000**
* `event-mysql`  – MySQL with the provided schemas, data persisted in `data/event-storage/`
* `event-phpmyadmin` – phpMyAdmin on **localhost:9001** (optional)

2. **Install frontend dependencies & run dev server**

```bash
cd frontend
npm install  # or pnpm / yarn
npm run dev
```

The React application will be available on **localhost:3000** and will automatically proxy all `/api/*` requests to the PHP container.

## Directory Layout

```
api/                PHP REST endpoints
  ├── auth/         – login / logout
  ├── customers/    – CRUD
  ├── inventory/    – CRUD
  ├── sales/        – CRUD
  ├── invoices/     – CRUD
  ├── reports/      – daily sales report
  └── settings/     – key-value settings
frontend/           React application (Vite + MUI)
mysql/              MySQL Dockerfile & schema SQL
```

## Default Credentials

* **Username:** `admin`
* **Password:** `admin123`

Credentials are stored in the `users` table (password hashed with `MD5` – change for production!).

## Next Steps

* Harden authentication (JWT, password hashing with bcrypt).
* Flesh out the remaining CRUD UIs (Inventory, Sales, Invoices).
* Add validations, tests, and proper error handling.

Enjoy! :rocket: