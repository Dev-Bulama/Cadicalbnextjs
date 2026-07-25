<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Renames the stored services.flutterwave.webhook_secret_hash setting row
 * (from the first V4 pass) to services.flutterwave.webhook_secret — same
 * value, no re-entry needed. See the earlier
 * 2026_07_25_040955_migrate_flutterwave_settings_to_v4_oauth migration for
 * the same pattern applied to client_id/client_secret.
 */
return new class extends Migration
{
    private const OLD_KEY = 'services.flutterwave.webhook_secret_hash';

    private const NEW_KEY = 'services.flutterwave.webhook_secret';

    public function up(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        $old = DB::table('settings')->where('key', self::OLD_KEY)->first();

        if (! $old) {
            return;
        }

        if (DB::table('settings')->where('key', self::NEW_KEY)->exists()) {
            return;
        }

        DB::table('settings')->insert([
            'key' => self::NEW_KEY,
            'value' => $old->value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        DB::table('settings')->where('key', self::NEW_KEY)->delete();
    }
};
