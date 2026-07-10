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

This is being rebuilt in phases (tracked in the session that created it):

- [x] **Phase 1** — Laravel scaffold, MySQL schema (35 tables mirroring the original Prisma schema),
      role-based auth (session + Sanctum), base Blade/Livewire/Tailwind-CDN layout with navbar/footer,
      seeders (8 demo accounts, 100 products across 9 categories, sample orders/bookings/notifications).
- [ ] **Phase 2** — Public storefront (home, products, cart, checkout, tracking, service booking, RFQ, etc.)
- [ ] **Phase 3** — Admin console (17 sections incl. analytics, audit logs, CRM integration hub)
- [ ] **Phase 4** — Supplier + technician portals, notifications, chat
- [ ] **Phase 5** — Zoho CRM sync engine, Pusher real-time, PWA, security hardening

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

| Role | Email |
|---|---|
| Super Admin | superadmin@cadical.com |
| Admin | admin@cadical.com |
| Supplier | supplier@cadical.com |
| Vendor | vendor@cadical.com |
| Technician | technician@cadical.com |
| Customer | customer@cadical.com |
| Hospital (Institution) | hospital@cadical.com |
| Free User | freeuser@cadical.com |
