<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class SettingsManager extends Component
{
    use WithFileUploads;

    /**
     * Field schema per group. Each field key is the dotted config() path it
     * overrides at runtime. 'password' fields are never re-displayed once
     * saved — leaving one blank on save keeps the existing stored value.
     */
    public const GROUPS = [
        'branding' => [
            'label' => 'Branding',
            'fields' => [
                'site.logo' => ['label' => 'Site Logo', 'type' => 'image', 'hint' => 'Shown in the navbar and footer. Leave blank to use the default logo.'],
                'site.logo_height' => ['label' => 'Logo Size', 'type' => 'select', 'options' => ['32' => 'Small (32px, default)', '40' => 'Medium (40px)', '48' => 'Large (48px)', '56' => 'X-Large (56px)', '64' => 'XX-Large (64px)', '80' => 'Huge (80px)', '96' => 'Maximum (96px)'], 'hint' => 'Height of the logo in the navbar and footer — width scales automatically, and the bar grows to fit it.'],
                'site.show_name' => ['label' => 'Site Name Text', 'type' => 'boolean', 'checkboxLabel' => 'Show "Cadical Solutions" text next to the logo', 'default' => true, 'hint' => 'Turn off if your uploaded logo already includes the company name.'],
            ],
        ],
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
        'integrations' => [
            'label' => 'Integrations',
            'fields' => [
                'site.chatbot_script' => ['label' => 'Chatbot / Live Chat Widget', 'type' => 'code', 'placeholder' => '<script src="..." data-chatbot-id="..."></script>', 'hint' => 'Paste the full <script> tag from your chat provider. It\'s added to every public page just before </body>.'],
            ],
        ],
    ];

    public string $activeGroup = 'branding';

    public array $values = [];

    /** @var array<string, bool> whether a password-type field currently has a stored value */
    public array $configured = [];

    /** @var TemporaryUploadedFile[] keyed by field index, for image-type fields */
    public array $valueFiles = [];

    public function mount(): void
    {
        $this->loadGroup($this->activeGroup);
    }

    public function switchGroup(string $key): void
    {
        $this->activeGroup = $key;
        $this->valueFiles = [];
        $this->loadGroup($key);
    }

    public function updated($property, $value): void
    {
        if (preg_match('/^valueFiles\.(\d+)$/', $property, $m) && $value instanceof TemporaryUploadedFile) {
            $index = (int) $m[1];
            $path = $value->store('branding', 'public');
            $this->values[$index] = '/storage/'.$path;
            unset($this->valueFiles[$index]);
        }
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
            $type = $meta['type'] ?? 'text';
            $stored = Setting::get($configKey);
            $current = $stored ?? config($configKey);

            if ($type === 'password') {
                $this->values[$i] = '';
                $this->configured[$i] = filled($current);
            } elseif ($type === 'boolean') {
                $this->values[$i] = $current === null
                    ? (bool) ($meta['default'] ?? false)
                    : in_array($current, [true, 1, '1'], true);
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

            $stored = match (true) {
                $type === 'boolean' => $value ? '1' : '0',
                $value === '' => null,
                default => $value,
            };
            Setting::set($configKey, $stored);
            config([$configKey => $stored]);
        }

        $this->loadGroup($this->activeGroup);

        $this->dispatch('cart-toast', message: self::GROUPS[$this->activeGroup]['label'].' saved');
    }

    public function render()
    {
        return view('livewire.admin.settings-manager', ['groups' => self::GROUPS]);
    }
}
