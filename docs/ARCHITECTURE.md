# Cadical Solutions (Laravel Rebuild) — Technical Architecture

> Laravel port of the original Next.js/Prisma application. Same interface, same feature set,
> different stack. This document describes what was actually built, not the original.

---

## 1. System Overview

Cadical Solutions is a healthcare supply-chain and field-service platform serving five user
personas — platform admins, institutional buyers, verified suppliers, field technicians, and
clinicians — through role-gated portals in a single Laravel codebase. It covers product
discovery and procurement (RFQ → bulk order → payment), field-service dispatch (booking →
technician assignment → job completion → maintenance scheduling), and syncs customer data to
Zoho CRM via an OAuth 2.0 integration engine. Order and job status updates broadcast over Pusher.
Payments are processed through Flutterwave and Paystack. Every significant admin mutation is
captured in an immutable audit log, and a global middleware applies security headers and API
rate limiting to every request.

---

## 2. Tech Stack

| Layer | Technology | Purpose |
|---|---|---|
| Framework | Laravel 13 (PHP 8.3+) | Routing, ORM, auth, queues, broadcasting |
| Interactivity | Livewire 4 + Alpine.js | Server-driven reactive components, no SPA build |
| Styling | Tailwind CSS via CDN | No frontend build step |
| Icons | Lucide (CDN, `lucide.createIcons()`) | Consistent icon set, re-rendered on Livewire updates |
| Charts | Chart.js (CDN) | Admin analytics dashboard |
| ORM | Eloquent | MySQL access, migrations |
| Database | MySQL 8 | Primary relational data store |
| Auth | Laravel session auth (web) + Sanctum | Cookie sessions for portals, bearer tokens for API |
| CRM | Zoho CRM (OAuth 2.0) | Bidirectional customer record sync |
| Real-time | Pusher (`pusher/pusher-php-server` + pusher-js CDN) | Broadcast order/job status changes |
| Payments | Flutterwave + Paystack | Dual payment gateway, server-side verification |
| Media | Cloudinary SDK | Product/document image uploads |
| PWA | `public/manifest.json` + `public/sw.js` | Installability, offline shell caching |
| Deployment | Docker (PHP-FPM + Nginx + MySQL) | See `Dockerfile`, `docker-compose.yml`, `nginx.conf` |

---

## 3. Folder Structure

```
/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/                 # DashboardController, AnalyticsController, CrmController,
│   │   │                          # CrmOAuthController (Zoho authorize/callback)
│   │   ├── Auth/                  # AuthenticatedSessionController, RegisteredUserController
│   │   ├── Clinician/             # DashboardController, OpportunitiesController
│   │   ├── Supplier/              # DashboardController
│   │   ├── PaymentController.php  # Flutterwave/Paystack verification, server-side price recompute
│   │   ├── ServicesController.php # Static services catalogue (public)
│   │   ├── TrackController.php    # Order tracking JSON API (/api/track/{code})
│   │   ├── NotificationsController.php
│   │   └── CrmWebhookController.php
│   │
│   ├── Livewire/
│   │   ├── Admin/                 # 15 components: Products/Orders/Bookings/Clinicians/
│   │   │                          # Suppliers/Rfq/Institutions/Referrals/ServiceJobs/
│   │   │                          # Technicians/Maintenance/Services/Tracking/AuditLogs/CrmManager
│   │   ├── Supplier/               # ProductsManager, OrdersManager, ProfileManager
│   │   ├── Technician/              # JobsBoard, ScheduleCalendar, ProfileManager, TrackingPanel
│   │   ├── Clinician/               # ProfileManager
│   │   ├── BookingWizard.php, ServiceBookingWizard.php, ReferralForm.php, RfqForm.php,
│   │   ├── SupplierRegisterWizard.php, InstitutionalPortal.php, ContactForm.php,
│   │   ├── ProductsCatalog.php, NotificationsCenter.php (shared)
│   │
│   ├── Models/                    # 36 Eloquent models mirroring the original Prisma schema
│   ├── Services/
│   │   ├── FlutterwaveService.php, PaystackService.php
│   │   └── Crm/ZohoCrmAdapter.php, Crm/CrmSyncService.php
│   ├── Events/                    # OrderTrackingUpdated, ServiceJobStatusUpdated (Pusher)
│   └── Http/Middleware/           # EnsureRole, SecurityHeaders
│
├── resources/views/
│   ├── layouts/                   # app.blade.php (public), admin.blade.php, portal.blade.php
│   │                              # (supplier/clinician), technician.blade.php (mobile bottom-nav)
│   ├── admin/, supplier/, technician/, clinician/   # wrapper pages per portal
│   ├── livewire/                  # component views, mirroring app/Livewire/* namespaces
│   └── components/admin/          # stat-card, badge Blade components
│
├── routes/
│   ├── web.php                    # public site + shared auth/notifications routes
│   ├── admin.php, supplier.php, technician.php, clinician.php   # role-gated route groups
│   └── api.php                    # /api/user (Sanctum), /api/crm/webhook
│
├── database/
│   ├── migrations/                # 35+ tables mirroring the original schema
│   └── seeders/DatabaseSeeder.php # demo accounts, products, sample records per portal
│
├── public/
│   ├── manifest.json, sw.js, icons/   # PWA
│   └── js/cart.js                     # Alpine global store (client-side cart, localStorage)
│
├── Dockerfile, docker-compose.yml, nginx.conf
└── bootstrap/app.php               # middleware registration (role alias, SecurityHeaders, throttle:api)
```

---

## 4. Database Schema Overview

All models live in `app/Models/`, defined with PHP 8 attributes (`#[Fillable([...])]`,
`#[Hidden([...])]`) rather than a `protected $fillable` array. 35+ migrations under
`database/migrations/` mirror the original Prisma schema table-for-table, with snake_case
columns per Laravel convention.

### Core Identity
`User` (role stored as free-form lowercase string: `superadmin`, `admin`, `clinician`,
`supplier`, `vendor`, `technician`, `customer`, `hospital`, `user`), `Clinician`,
`TechnicianProfile`.

### Commerce
`Product`, `CartItem` (unused server-side — cart is client-only, see §7), `Order`, `OrderItem`,
`TrackingEvent`.

### Institutional / B2B
`Institution`, `Document`, `Referral`.

### Supplier Marketplace
`Supplier`, `SupplierDocument`, `SupplierProduct`, `Rfq`, `RfqBid`, `BulkOrder`.

### Service Ecosystem
`Booking` (general booking form), `ServiceBooking`, `ServiceStatusEvent`, `ServiceJob`,
`MaintenanceSchedule`, `MaintenanceLog`, `EquipmentRecord`, `ServiceContract`.

### CRM Integration Engine
`CrmConnection`, `CrmFieldMapping`, `CrmSyncLog`, `CrmAutomationRule`, `CrmWebhookLog`,
`CrmFailedJob` — see §8.

### Supporting Models
`AppNotification`, `AuditLog`, `ChatRoom`/`ChatMessage` (schema exists, ported from the
original, but unused — the original app never built a chat UI against them either).

---

## 5. Authentication Architecture

Laravel's built-in session-based `web` guard handles all portal logins (`routes/web.php`
`/auth/login`, `/auth/register`, `/auth/logout`) — no third-party auth package. Sessions are
stored in the `sessions` table (`SESSION_DRIVER=database`). Sanctum is installed for the
technician mobile/PWA API surface (`routes/api.php`, `/api/user`), issuing personal access
tokens rather than the original's separate JWT scheme.

### Role Enforcement

`User::ROLE_REDIRECTS` maps each role to its post-login landing route
(`app/Models/User.php`), read via `$user->redirectPath()` from the shared `/dashboard` redirect
route. Route-level protection is a Laravel middleware, not a global request interceptor like
the original's `middleware.ts`:

```php
// app/Http/Middleware/EnsureRole.php
Route::middleware(['auth', 'role:'.User::ROLE_SUPER_ADMIN.','.User::ROLE_ADMIN])
    ->prefix('admin')->group(...);
```

Each portal (`admin`, `supplier`, `technician`, `clinician`) has its own `routes/*.php` file
wrapped in a `role:` middleware group, rather than one central path-matching table.

---

## 6. Request Handling Conventions

Unlike the original's `app/api/*` REST route handlers, most mutations in this rebuild happen
through Livewire component methods (`wire:click="updateStatus(1, 'DELIVERED')"`) called directly
from Blade views — there is no separate JSON API layer for the admin/portal CRUD operations.
This is a deliberate stack-appropriate substitution: Livewire's request/response cycle already
provides the same client → server → re-render loop the original achieved with `fetch()` calls
into API routes, without hand-rolling a REST contract for internal-only UI.

Genuine API endpoints (used by external callers or public JS, not portal UI) remain plain
routes:

| Route | Description |
|---|---|
| `GET /api/track/{code}` | Public order tracking lookup, consumed by Alpine on `/track` |
| `POST /api/crm/webhook` | Zoho inbound webhook receiver (CSRF-exempt, in `routes/api.php`) |
| `GET /api/user` | Sanctum-authenticated current user (technician mobile/PWA) |
| `POST /checkout/verify` | Flutterwave/Paystack payment verification |
| `GET /admin/integrations/crm/zoho/authorize` \| `.../callback` | OAuth redirect flow |

---

## 7. Client-Side Cart

The original stores cart state entirely client-side (React Context + `localStorage`), despite a
`CartItem` model existing in the schema. This rebuild preserves that exact behavior rather than
"fixing" it into a server-persisted cart: `public/js/cart.js` registers an
`Alpine.store('cart', {...})` global store backed by `localStorage`, and the `CartItem` model
exists in migrations but is not written to by any current flow.

---

## 8. Real-Time Architecture (Pusher)

### Server-Side Trigger

Two Laravel broadcast events fire on public channels, matching the original's channel/event
naming exactly:

```php
// app/Events/OrderTrackingUpdated.php — channel: order-{orderId}, event: tracking-update
// app/Events/ServiceJobStatusUpdated.php — channel: booking-{bookingId}, event: status-update
```

Both implement `ShouldBroadcastNow` (synchronous, no queue worker required) but are never
dispatched directly — always through a static `fire()` helper:

```php
OrderTrackingUpdated::fire($trackingEvent);
```

`fire()` no-ops when `services.pusher.key` is unconfigured and wraps the actual dispatch in a
try/catch otherwise, so a missing or unreachable Pusher connection can never break the admin
action that triggered it — this exactly mirrors the original's own
`try { await pusherServer.trigger(...) } catch { /* Pusher is optional */ }` pattern in
`app/api/service-jobs/route.ts`.

Call sites: `Admin\OrdersManager::updateStatus()`, `Admin\TrackingManager::addEvent()`,
`Admin\ServiceJobsManager::updateStatus()`/`assignTechnician()`, `Technician\JobsBoard`'s
accept/decline/advance actions.

### Client-Side Subscription

The public `/track` page (`resources/views/track.blade.php`) loads `pusher-js` from CDN (only
when `services.pusher.key` is configured) and subscribes to `order-{id}` once a tracking lookup
resolves, prepending live `tracking-update` events to the displayed history and updating the
status badge — no page reload.

---

## 9. CRM Integration Architecture

### Overview

`app/Services/Crm/` provides a single-provider (Zoho) implementation rather than the original's
abstract-adapter-with-one-implementation pattern — `ZohoCrmAdapter` is called directly by
`CrmSyncService` rather than through a `CrmAdapter` interface, since there is exactly one
provider and no near-term need for a second.

### Zoho OAuth 2.0 Flow

```
1. Admin opens /admin/integrations/crm, fills in Client ID/Secret/Redirect URI (Admin\CrmManager)
2. Admin clicks "Connect to Zoho" → GET /admin/integrations/crm/zoho/authorize
   (CrmOAuthController::authorize) → redirects to accounts.zoho.com/oauth/v2/auth
3. Admin approves access in Zoho
4. Zoho redirects to /admin/integrations/crm/zoho/callback?code=...&state={connection_id}
5. CrmOAuthController::callback exchanges the code for access/refresh tokens via
   ZohoCrmAdapter::exchangeCodeForTokens(), stores them on CrmConnection,
   sets is_connected=true, health_score=100
```

### Sync Engine (`CrmSyncService`)

Five sync methods, each writing a `CrmSyncLog` row (`running` → `success`/`partial`/`failed`),
triggered manually from the CRM admin page (per-entity or "Sync All" buttons):

| Cadical Entity | Zoho Module | Direction |
|---|---|---|
| `User` | Contacts | Push (search-then-update-or-create) |
| `Institution` | Accounts | Push (search-then-update-or-create) |
| `Order` (status=PAID) | Deals | Push |
| `Booking` | Cases | Push |
| `Referral` | Leads | Push |

Scheduled/interval-based sync (the original's `sync_interval` field on `CrmConnection`) is
stored but not yet wired to a scheduled command — all syncs in this rebuild are admin-triggered.

### Webhook Handler

`POST /api/crm/webhook` (`CrmWebhookController`) writes a `CrmWebhookLog` row per inbound event
and marks it `processed`. Deeper state mapping (e.g., a Zoho deal-stage change reflecting back
onto an `Order.status`) is left as a documented extension point, matching the original's own
`// Future: map back to Cadical order status` comment in its webhook handler.

### Failed Jobs

`Admin\CrmManager::retryFailedJob()` increments `retry_count` and sets `status=retrying` (or
`abandoned` once `max_retries` is reached), matching the original's retry semantics exactly.

---

## 10. Security Layers

### Middleware

`app/Http/Middleware/SecurityHeaders.php`, registered globally in `bootstrap/app.php`, sets the
same five headers as the original's `middleware.ts` on every response:

| Header | Value |
|---|---|
| `X-Content-Type-Options` | `nosniff` |
| `X-Frame-Options` | `DENY` |
| `X-XSS-Protection` | `1; mode=block` |
| `Referrer-Policy` | `strict-origin-when-cross-origin` |
| `Permissions-Policy` | `camera=(), microphone=(), geolocation=(self)` |

### Rate Limiting

Rather than the original's single-process in-memory token bucket (explicitly noted there as
not multi-instance-safe), this rebuild uses Laravel's cache-backed `RateLimiter`, registered in
`AppServiceProvider::boot()` and applied via `throttle:api` prepended to the `api` middleware
group in `bootstrap/app.php`:

```php
RateLimiter::for('api', fn (Request $r) => Limit::perMinute(100)->by($r->user()?->id ?: $r->ip()));
```

This is backed by Laravel's cache driver (`CACHE_STORE`), so it works correctly across multiple
app instances/workers, unlike the original's per-process `Map`.

### Audit Logging

`AuditLog` model + `Admin\AuditLogsManager` Livewire component provide the same read surface as
the original. Writes currently happen from the seeder and are a documented extension point for
wiring into every admin mutation (approve/reject/update actions) — not yet done for every single
admin action in this rebuild.

### Payment Security Fix (deliberate deviation)

The original trusts a client-submitted amount at checkout. `PaymentController::verify()` in this
rebuild recomputes the order total server-side from `Product` records before creating the
`Order`/`OrderItem` rows, closing a price-tampering vector present in the original. This was a
deliberate improvement, flagged per the project's standing instruction not to reproduce known
security vulnerabilities from a source being ported.

---

## 11. PWA Architecture

### Web App Manifest (`public/manifest.json`)

Ported from the original verbatim (name, theme color `#1565C0`, shortcuts to
`/service-booking`, `/dashboard`, `/rfq`). Icons were missing from the original repository
(the manifest referenced `/icons/icon-*.png` paths with no corresponding files ever committed);
this rebuild generates an actual icon set from the existing brand logo via PHP GD
(`public/icons/icon-{72,96,128,192,512}.png`), so "Add to Home Screen" is genuinely installable.

### Service Worker (`public/sw.js`)

Ported from the original with one fix: its static-asset cache-first rule targeted Next.js's
`/_next/static/` path, which doesn't exist in this stack. Updated to match this app's actual
static paths (`/js/`, `/css/`, `/icons/`, `/build/`). Registered from `layouts/app.blade.php`
(public site) and `layouts/technician.blade.php` (mobile portal) — the two surfaces where
install/offline behavior matters most.

---

## 12. Deployment

`Dockerfile` is a two-stage build: a `composer:2` stage installs PHP dependencies, then a
`php:8.3-fpm-alpine` runtime stage copies the app and vendor directory, running as a non-root
`laravel` user. `docker-compose.yml` wires together `mysql`, `app` (PHP-FPM), `nginx` (serving
`public/` and proxying `.php` requests to `app:9000`), and a one-shot `migrate` service.
`nginx.conf` carries over the original's security headers, gzip, and rate-limit zones, adapted
for a PHP-FPM upstream instead of a Node process, with `/icons/`, `/manifest.json`, and `/sw.js`
given the same cache treatment as the original.

No changes were needed to `.github/workflows/tests.yml` — it already runs `php artisan test`
against a matrix of PHP versions on every push, which is the Laravel-idiomatic equivalent of the
original's lint/build/db-check CI pipeline.
