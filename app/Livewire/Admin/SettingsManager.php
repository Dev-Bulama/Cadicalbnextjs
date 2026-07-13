<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use App\Providers\SettingsServiceProvider;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class SettingsManager extends Component
{
    /**
     * Field schema per group. Each field key is the dotted config() path it
     * overrides at runtime. 'password' fields are never re-displayed once
     * saved — leaving one blank on save keeps the existing stored value.
     */
    public const GROUPS = [
        'payments' => [
            'label' => 'Payment Gateways',
            'fields' => [
                'services.flutterwave.public_key' => ['label' => 'Flutterwave Public Key', 'type' => 'text', 'env' => 'FLUTTERWAVE_PUBLIC_KEY'],
                'services.flutterwave.secret_key' => ['label' => 'Flutterwave Secret Key', 'type' => 'password', 'env' => 'FLUTTERWAVE_SECRET_KEY'],
                'services.flutterwave.encryption_key' => ['label' => 'Flutterwave Encryption Key', 'type' => 'password', 'env' => 'FLUTTERWAVE_ENCRYPTION_KEY'],
                'services.paystack.public_key' => ['label' => 'Paystack Public Key', 'type' => 'text', 'env' => 'PAYSTACK_PUBLIC_KEY'],
                'services.paystack.secret_key' => ['label' => 'Paystack Secret Key', 'type' => 'password', 'env' => 'PAYSTACK_SECRET_KEY'],
            ],
        ],
        'mail' => [
            'label' => 'Mail (SMTP)',
            'fields' => [
                'mail.default' => ['label' => 'Mail Driver', 'type' => 'select', 'options' => ['log' => 'Log (dev only — no real email sent)', 'smtp' => 'SMTP'], 'env' => 'MAIL_MAILER'],
                'mail.mailers.smtp.host' => ['label' => 'SMTP Host', 'type' => 'text', 'placeholder' => 'e.g. smtp.gmail.com', 'env' => 'MAIL_HOST'],
                'mail.mailers.smtp.port' => ['label' => 'SMTP Port', 'type' => 'text', 'placeholder' => 'e.g. 587', 'env' => 'MAIL_PORT'],
                'mail.mailers.smtp.username' => ['label' => 'SMTP Username', 'type' => 'text', 'env' => 'MAIL_USERNAME'],
                'mail.mailers.smtp.password' => ['label' => 'SMTP Password', 'type' => 'password', 'env' => 'MAIL_PASSWORD'],
                'mail.mailers.smtp.scheme' => ['label' => 'Encryption', 'type' => 'select', 'options' => ['' => 'None', 'tls' => 'TLS', 'ssl' => 'SSL'], 'env' => 'MAIL_SCHEME'],
                'mail.from.address' => ['label' => 'From Address', 'type' => 'text', 'env' => 'MAIL_FROM_ADDRESS'],
                'mail.from.name' => ['label' => 'From Name', 'type' => 'text', 'env' => 'MAIL_FROM_NAME'],
            ],
        ],
    ];

    public string $activeGroup = 'payments';

    public array $values = [];

    /** @var array<string, bool> whether a password-type field currently has a stored value */
    public array $configured = [];

    public function mount(): void
    {
        $this->loadGroup($this->activeGroup);
    }

    public function switchGroup(string $key): void
    {
        $this->activeGroup = $key;
        $this->loadGroup($key);
    }

    /**
     * $values and $configured are indexed positionally (not by the dotted
     * config key) because Livewire treats dots in a wire:model path as
     * nested array access rather than a literal string key.
     */
    private function loadGroup(string $key): void
    {
        $fields = self::GROUPS[$key]['fields'];
        $this->values = [];
        $this->configured = [];

        $i = 0;
        foreach ($fields as $configKey => $meta) {
            $stored = Setting::get($configKey);
            $current = $stored ?? config($configKey);

            if (($meta['type'] ?? 'text') === 'password') {
                $this->values[$i] = '';
                $this->configured[$i] = filled($current);
            } else {
                $this->values[$i] = (string) ($current ?? '');
            }
            $i++;
        }
    }

    public function save(): void
    {
        $fields = self::GROUPS[$this->activeGroup]['fields'];
        $configKeys = array_keys($fields);

        foreach ($this->values as $i => $value) {
            $configKey = $configKeys[$i];
            $type = $fields[$configKey]['type'] ?? 'text';

            if ($type === 'password' && $value === '') {
                continue;
            }

            $stored = $value === '' ? null : $value;
            Setting::set($configKey, $stored);
            config([$configKey => $stored]);
        }

        Cache::forget(SettingsServiceProvider::CACHE_KEY);
        $this->loadGroup($this->activeGroup);

        $this->dispatch('cart-toast', message: self::GROUPS[$this->activeGroup]['label'].' saved');
    }

    public function render()
    {
        return view('livewire.admin.settings-manager', ['groups' => self::GROUPS]);
    }
}
