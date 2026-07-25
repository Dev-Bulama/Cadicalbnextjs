<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Throwable;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Keys whose stored value is encrypted at rest (payment/API secrets).
     * Encryption/decryption happens transparently in get()/set()/allKeyed()
     * so callers (SettingsManager, SettingsServiceProvider) never touch
     * ciphertext directly.
     */
    public const ENCRYPTED_KEYS = [
        'services.flutterwave.client_secret',
        'services.flutterwave.encryption_key',
        'services.flutterwave.webhook_secret_hash',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = static::where('key', $key)->value('value');

        if ($value === null) {
            return $default;
        }

        return in_array($key, self::ENCRYPTED_KEYS, true) ? self::tryDecrypt($value) : $value;
    }

    public static function set(string $key, ?string $value): void
    {
        $stored = ($value !== null && $value !== '' && in_array($key, self::ENCRYPTED_KEYS, true))
            ? Crypt::encryptString($value)
            : $value;

        static::updateOrCreate(['key' => $key], ['value' => $stored]);
    }

    /** @return array<string, string> */
    public static function allKeyed(): array
    {
        return static::all()
            ->mapWithKeys(fn ($row) => [
                $row->key => in_array($row->key, self::ENCRYPTED_KEYS, true) ? self::tryDecrypt($row->value) : $row->value,
            ])
            ->all();
    }

    /**
     * Falls back to returning the raw value on failure so a legacy plaintext
     * row (e.g. one copied over by the V3→V4 settings migration, before it's
     * ever been re-saved through set()) doesn't break the app — it just
     * isn't decrypted, same as if encryption had never been introduced.
     */
    private static function tryDecrypt(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }

        try {
            return Crypt::decryptString($value);
        } catch (Throwable) {
            return $value;
        }
    }
}
