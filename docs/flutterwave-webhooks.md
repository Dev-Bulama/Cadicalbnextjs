# Flutterwave V4 Webhook Integration

This documents how Cadical Solutions receives and processes Flutterwave payment
notifications. It covers the webhook URL, required setup, the verification/order-update
flow, retry and idempotency behavior, and how to observe/debug it.

## Webhook URL

```
POST https://<your-domain>/api/webhooks/flutterwave
```

Displayed automatically (with a copy button) at **/admin/settings → Payment Gateways**,
alongside a **Configured** / **Not Configured** status badge. Register this exact URL in
your Flutterwave dashboard's webhook settings.

The route is registered in `routes/api.php`, which — like the existing
`CrmWebhookController` — has no CSRF middleware, since Flutterwave's servers can't supply
a Laravel session/CSRF token. Only `POST` is accepted.

## Required setup

Both of these are set at **/admin/settings → Payment Gateways** (or via `.env`):

| Setting | Purpose |
|---|---|
| Flutterwave Client ID / Client Secret | OAuth2 credentials used to call Flutterwave's API (`FlutterwaveService`) |
| Flutterwave Webhook Secret | Shared secret used to verify that an incoming webhook call actually came from Flutterwave |

The **Test Flutterwave Connection** button on that page checks both: it fetches a live
OAuth token with the credentials currently in the form (whether saved or not), and checks
whether a webhook secret is configured, then reports one of:

- **✔ Everything Ready** — OAuth connected and a webhook secret is set.
- **✗ Authentication failed** — the Client ID/Secret are rejected by Flutterwave.
- **⚠ OAuth connected, but no webhook secret is configured** — payments can be taken, but
  incoming webhooks will be rejected until a secret is set.

Testing never saves anything — it only reports status.

## Required headers

Flutterwave signs the raw request body and sends the signature in one of two headers,
depending on account/API version:

- `flutterwave-signature` — HMAC-SHA256 of the raw body, keyed with the webhook secret.
  Verified with `hash_equals(hash_hmac('sha256', $rawBody, $secret), $header)`.
- `verif-hash` (legacy) — the webhook secret sent directly for a constant-time string
  comparison.

`FlutterwaveService::verifyWebhookSignature()` accepts either. A request with neither
header, an incorrect signature, or no webhook secret configured at all is rejected with
**HTTP 403** before any payload is parsed or logged in detail.

## Webhook flow

```
Flutterwave ──POST──▶ FlutterwaveWebhookController::handle()
                          │
                          ├─ 1. Verify signature (flutterwave-signature / verif-hash)
                          │     invalid → 403, nothing else happens
                          │
                          ├─ 2. Parse payload, require data.reference
                          │     missing/malformed → 422
                          │
                          ├─ 3. Look up Order by tracking_code = data.reference
                          │     AND payment_method = FLUTTERWAVE
                          │     not found → 200 {processed:false, reason:order_not_found}
                          │
                          ├─ 4. Order has no stored payment_id (charge id)
                          │     → 200 {processed:false, reason:no_charge_id}
                          │
                          ├─ 5. Already processed this exact charge id (order is PAID
                          │     with a matching flutterwave_transaction_id)
                          │     → 200 {processed:false, reason:duplicate}
                          │
                          ├─ 6. Order status isn't PENDING (finalized some other way,
                          │     e.g. the browser redirect callback beat this webhook here)
                          │     → 200 {processed:false, reason:already_finalized}
                          │
                          ├─ 7. Call FlutterwaveService::verifyTransaction($order->payment_id)
                          │     — this is the ONLY thing the paid/failed decision is based
                          │     on. The payload's own `status`/`type` fields are used only
                          │     to log context and to locate the order in step 3 — never to
                          │     decide the outcome. A forged-but-validly-signed payload
                          │     claiming success can't fake a payment: it just triggers a
                          │     real re-check against Flutterwave's API.
                          │
                          ├─ 8. Verification says not successful
                          │     → Order::markFlutterwaveFailed(), 200 {processed:false}
                          │
                          ├─ 9. Verification says successful, but the verified amount/
                          │     currency doesn't match the order's own total
                          │     → order left untouched, 422 {error:amount_mismatch}
                          │       (this should never happen legitimately — it's logged at
                          │       error level for investigation)
                          │
                          └─ 10. Verified successful, amount matches
                                → Order::markFlutterwavePaid($result), 200 {processed:true}
```

`FlutterwaveCheckoutController::callback()` (the customer's browser redirect back from
Flutterwave's hosted checkout page) runs the same verify → amount-check → finalize logic,
so whichever of the two — webhook or browser redirect — arrives first wins, and the other
is a safe no-op. Neither path trusts anything from the browser/webhook beyond "which order
to check."

## Order update flow

On success, `Order::markFlutterwavePaid()` sets, atomically:

| Column | Value |
|---|---|
| `status` | `PAID` (the single source of truth for order lifecycle used everywhere else in the app — admin orders list, customer order history, tracking page) |
| `paid_at` | now() |
| `processed_at` | now() |
| `flutterwave_transaction_id` | Flutterwave's charge id |
| `payment_reference` | the verified transaction reference |
| `gateway_response` | the full raw verification API response (JSON) |
| `payment_metadata` | `{amount, currency, customer_email}` extracted from the verification response (JSON) |

...plus a `TrackingEvent` row (`status: PAID`) for the customer-facing order timeline.

On failure, `Order::markFlutterwaveFailed()` sets `status = CANCELLED`, `processed_at`,
and records the transaction id/raw response if available, plus a `CANCELLED` tracking
event.

Both methods are guarded by `if ($this->status !== self::STATUS_PENDING) return;` — this
is the core idempotency mechanism. `Order::hasProcessedFlutterwaveTransaction()` provides
an earlier, more specific "duplicate" signal for logging/response purposes, checked before
re-verifying with Flutterwave's API.

## Retry behavior

Flutterwave (like most webhook senders) may redeliver the same event — network blips,
timeouts, or a non-2xx response from us. This integration is safe to retry:

- A **200** duplicate/no-op response is always idempotent — redelivering a webhook for an
  already-PAID order never double-charges, double-emails, or double-creates tracking events.
- A **422** (malformed payload or amount mismatch) is safe to retry too, since nothing was
  written — but a persistent amount mismatch indicates a real problem worth investigating,
  not a transient one that retrying will fix.
- A **403** (invalid signature) will keep failing on retry until the webhook secret
  configured here matches what's set in the Flutterwave dashboard.
- A **500** (unexpected exception) is logged with file/line (never secrets) and is safe to
  retry — the whole handler runs inside a try/catch that never partially commits an order
  state change before failing.

## Logging

All Flutterwave activity — OAuth, charge init/verify, and every step of the webhook flow
above — logs to a dedicated channel (`config/logging.php` → `flutterwave`), writing to
`storage/logs/flutterwave-*.log` (daily rotation), separate from the main application log.

Never logged, anywhere in this integration: the Client Secret, the Encryption Key, the
Webhook Secret, or an OAuth access token. Log calls only ever receive identifiers
(tracking codes, charge ids, HTTP status codes) and non-sensitive context.

## Files

| File | Responsibility |
|---|---|
| `app/Services/FlutterwaveService.php` | OAuth token fetch/cache/refresh, Charges API (initiate/verify), webhook signature verification, connection diagnostics. The only place that talks to Flutterwave's API directly. |
| `app/Http/Controllers/FlutterwaveWebhookController.php` | Receives, verifies, and processes webhook deliveries (this document's main flow) |
| `app/Http/Controllers/FlutterwaveCheckoutController.php` | Creates the pending order + hosted checkout link (`initiate`), and handles the browser's redirect back (`callback`) |
| `app/Models/Order.php` | `markFlutterwavePaid()`, `markFlutterwaveFailed()`, `verifiedAmountMatches()`, `hasProcessedFlutterwaveTransaction()` |
| `app/Livewire/Admin/SettingsManager.php` | Credential/webhook-secret management, webhook URL display, Test Connection |
| `database/migrations/2026_07_25_054731_add_payment_gateway_details_to_orders_table.php` | `paid_at`, `flutterwave_transaction_id`, `payment_reference`, `gateway_response`, `payment_metadata`, `processed_at` columns |
