# Cadical Solutions — Laravel Rebuild

Laravel port of the [Cadical Solutions](https://github.com/Dev-Bulama/CadicalSolutions) healthcare
supply-chain and field-service platform. Same interface and feature set, rebuilt on a different stack.

## Stack

| Original (Next.js) | This rebuild |
|---|---|
| Next.js 16 / React 19 | Laravel 13, Blade views |
| Prisma / PostgreSQL | Eloquent / MySQL |
| better-auth | Laravel session auth (web) + Sanctum (technician mobile/API) |
| Tailwind CSS (build) | Tailwind CSS via CDN (no build step) |
| Client interactivity (React state) | Livewire + Alpine.js |
| Pusher, Cloudinary, Flutterwave, Zoho CRM, AfterShip | Same services, via their PHP/Laravel SDKs |

## Status

Rebuilt in five phases (see [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md) for the full breakdown):

- [x] **Phase 1** — Laravel scaffold, MySQL schema (35+ tables mirroring the original Prisma schema),
      role-based auth (session + Sanctum), base Blade/Livewire/Tailwind-CDN layout with navbar/footer,
      seeders (demo accounts, 100 products across 9 categories, sample orders/bookings/notifications).
- [x] **Phase 2** — Public storefront: home, product catalog, cart, checkout (Flutterwave + Paystack),
      order tracking, booking wizards, RFQ, referrals, institutional portal, services, static pages.
- [x] **Phase 3** — Admin console: 17 sections including dashboard, products, orders, bookings,
      clinicians, suppliers, RFQ, institutions, referrals, service jobs, technicians, maintenance,
      services, tracking, Chart.js analytics, audit logs, and a CRM integration hub.
- [x] **Phase 4** — Self-service portals for suppliers (registration wizard, dashboard, products,
      orders, profile), technicians (mobile job board, schedule, live tracking, profile), and
      clinicians (dashboard, opportunities, profile), plus a shared database-backed notifications center.
- [x] **Phase 5** — Zoho CRM sync engine (OAuth2, field mapping, automation rules, webhook receiver),
      Pusher real-time order/job status broadcasting, PWA (manifest, service worker, install icons),
      security headers + API rate limiting, and Docker/Nginx deployment config.

## Local setup

```bash
composer install
cp .env.example .env
php artisan key:generate
# create a MySQL database named `cadical` and set DB_* in .env
php artisan migrate
php artisan db:seed
php artisan serve
```

## Demo accounts

All accounts use password `Cadical@2026`.

| Role | Email | Redirects to |
|---|---|---|
| Super Admin | superadmin@cadical.com | /admin/dashboard |
| Admin | admin@cadical.com | /admin/dashboard |
| Supplier | supplier@cadical.com | /supplier/dashboard |
| Vendor | vendor@cadical.com | /supplier/dashboard |
| Technician | technician@cadical.com | /technician/jobs |
| Clinician | clinician@cadical.com | /clinician/dashboard |
| Customer | customer@cadical.com | /products |
| Hospital (Institution) | hospital@cadical.com | /products |
| Free User | freeuser@cadical.com | /products |

## Docker

A production-like local stack (MySQL + PHP-FPM + Nginx) is provided:

```bash
cp .env.example .env
docker compose up -d --build
docker compose run --rm migrate   # applies migrations once mysql is healthy
```

The `nginx` service listens on `http://localhost:8000`. See `Dockerfile`, `docker-compose.yml`,
and `nginx.conf` for details — these mirror the original app's deployment setup, swapped for
PHP-FPM/Nginx instead of the Next.js standalone Node server, and MySQL instead of PostgreSQL.
