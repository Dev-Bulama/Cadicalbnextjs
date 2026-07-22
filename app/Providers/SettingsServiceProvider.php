<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Throwable;

class SettingsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        try {
            if (! Schema::hasTable('settings')) {
                return;
            }

            // Read straight from the DB on every request rather than caching —
            // this table stays small and is read once per request, and a stale
            // cache here previously meant an admin-saved change (e.g. the site
            // logo) could silently fail to appear on the public site.
            foreach (Setting::allKeyed() as $key => $value) {
                if ($value !== null && $value !== '') {
                    config([$key => $value]);
                }
            }
        } catch (Throwable) {
            // DB not reachable yet (e.g. during initial migrate) — fall back to .env config.
        }
    }
}
