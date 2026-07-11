# GarageFlow

GarageFlow is a Laravel + Vue garage management system for customer intake, vehicle tracking, job cards, workshop execution, inventory, invoicing, and reminders.

## Stack

- Laravel 13 starter runtime with PHP 8.3+
- Vue 3 + Inertia.js
- Tailwind CSS
- MySQL or SQLite for local testing
- Spatie Laravel Permission
- Laravel Excel
- DomPDF

## Features

- Role-ready users for `admin`, `service_advisor`, and `mechanic`
- Dashboard with revenue, jobs, stock alerts, and reminder widgets
- Customer, vehicle, mechanic, service, inventory, job card, and invoice modules
- PDF invoice export
- Excel revenue export
- Queue-ready customer notifications for completed jobs and reminders
- Seeded demo workshop data
- Docker support

## Local Setup

```bash
fnm use 22
cp .env.example .env
composer install
npm install
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

Default seeded users:

- `admin@garageflow.test` / `password`
- `advisor@garageflow.test` / `password`
- `mechanic@garageflow.test` / `password`

## Docker

```bash
docker compose up --build
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

## Testing

```bash
php artisan test
```

## Notes

- The seeded demo environment uses SQLite-friendly defaults for local development.
- Frontend builds should run on Node 22+; the checked-in Dockerfile and `.nvmrc` use that runtime.
- Queue workers can be started with `php artisan queue:work` once mail/notification credentials are configured.
