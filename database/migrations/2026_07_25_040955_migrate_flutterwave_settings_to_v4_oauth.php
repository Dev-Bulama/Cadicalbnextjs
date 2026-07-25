<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The `settings` table is a generic key/value store (see App\Models\Setting),
 * not fixed columns, so "renaming a column" here means renaming the stored
 * row keys. This preserves whatever was in the old V3 public_key/secret_key
 * slots rather than deleting them — but those values are a fundamentally
 * different credential type (V3 API keys, not V4 OAuth Client ID/Secret) and
 * will NOT authenticate against Flutterwave's V4 API as-is. An admin must
 * still enter real V4 credentials via /admin/settings → Payment Gateways;
 * this migration only prevents the upgrade from silently dropping data and
 * keeps the settings row present so the field isn't blank/confusing.
 */
return new class extends Migration
{
    private const RENAMES = [
        'services.flutterwave.public_key' => 'services.flutterwave.client_id',
        'services.flutterwave.secret_key' => 'services.flutterwave.client_secret',
    ];

    public function up(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        foreach (self::RENAMES as $oldKey => $newKey) {
            $old = DB::table('settings')->where('key', $oldKey)->first();

            if (! $old) {
                continue;
            }

            $newExists = DB::table('settings')->where('key', $newKey)->exists();

            if ($newExists) {
                // Don't clobber a value an admin may have already entered for V4.
                continue;
            }

            DB::table('settings')->insert([
                'key' => $newKey,
                'value' => $old->value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        foreach (self::RENAMES as $oldKey => $newKey) {
            DB::table('settings')->where('key', $newKey)->delete();
        }
    }
};
