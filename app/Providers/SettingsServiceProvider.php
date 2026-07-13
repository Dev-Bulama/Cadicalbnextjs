<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Throwable;

class SettingsServiceProvider extends ServiceProvider
{
    public const CACHE_KEY = 'app.settings.overrides';

    public function boot(): void
    {
        try {
            if (! Schema::hasTable('settings')) {
                return;
            }

            $overrides = Cache::rememberForever(self::CACHE_KEY, fn () => Setting::allKeyed());

            foreach ($overrides as $key => $value) {
                if ($value !== null && $value !== '') {
                    config([$key => $value]);
                }
            }
        } catch (Throwable) {
            // DB not reachable yet (e.g. during initial migrate) — fall back to .env config.
        }
    }
}
