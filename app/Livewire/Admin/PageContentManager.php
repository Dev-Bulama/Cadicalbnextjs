<?php

namespace App\Livewire\Admin;

use App\Models\HomeSection;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class PageContentManager extends Component
{
    use WithFileUploads;

    /**
     * Same schema-driven engine as HomeContentManager, covering the other
     * public pages (About, Contact, Terms, Privacy, Referral form, Booking
     * wizards) instead of the homepage. Kept as a separate component/tab
     * list purely so the admin sidebar doesn't grow to 25 entries in one
     * flat list.
     */
    public const SECTIONS = [
        'about' => [
            'label' => 'About — Hero & CTA', 'itemLabel' => null,
            'metaFields' => [
                'hero_badge' => 'text', 'hero_heading' => 'text', 'hero_paragraph' => 'textarea',
                'hero_cta1_label' => 'text', 'hero_cta1_href' => 'text',
                'hero_cta2_label' => 'text', 'hero_cta2_href' => 'text',
                'what_heading' => 'text', 'what_sub' => 'textarea',
                'cta_heading' => 'text', 'cta_paragraph' => 'textarea',
                'cta_button_label' => 'text', 'cta_button_href' => 'text',
            ],
            'itemFields' => [],
        ],
        'about_services' => [
            'label' => 'About — What We Do', 'itemLabel' => 'Service Card',
            'metaFields' => [],
            'itemFields' => ['icon' => 'text', 'title' => 'text', 'desc' => 'textarea', 'bullets' => 'array'],
        ],
        'about_values' => [
            'label' => 'About — Mission/Vision/Values', 'itemLabel' => 'Card',
            'metaFields' => [],
            'itemFields' => ['icon' => 'text', 'title' => 'text', 'text' => 'textarea'],
        ],
        'contact' => [
            'label' => 'Contact Page', 'itemLabel' => null,
            'metaFields' => [
                'heading' => 'text', 'sub' => 'textarea', 'email' => 'text', 'phone' => 'text',
                'cta_heading' => 'text', 'cta_paragraph' => 'textarea', 'cta_button_label' => 'text',
            ],
            'itemFields' => [],
        ],
        'terms' => [
            'label' => 'Terms of Use', 'itemLabel' => 'Clause',
            'metaFields' => ['heading' => 'text'],
            'itemFields' => ['title' => 'text', 'body' => 'textarea'],
        ],
        'privacy' => [
            'label' => 'Privacy Policy', 'itemLabel' => 'Clause',
            'metaFields' => ['heading' => 'text', 'effective_date' => 'text'],
            'itemFields' => ['title' => 'text', 'body' => 'textarea', 'bullets' => 'array'],
        ],
        'referral_form' => [
            'label' => 'Referral Form', 'itemLabel' => null,
            'metaFields' => [
                'banner_heading' => 'text', 'banner_sub' => 'text',
                'form_title' => 'text', 'form_sub' => 'text',
                'section1_title' => 'text', 'section1_sub' => 'textarea',
                'section2_title' => 'text', 'section2_sub' => 'textarea',
                'section3_title' => 'text', 'section3_sub' => 'textarea',
                'section4_title' => 'text', 'section4_sub' => 'textarea',
                'confirmation_heading' => 'text', 'confirmation_message' => 'textarea',
            ],
            'itemFields' => [],
        ],
        'booking_wizard' => [
            'label' => 'Booking Wizard — Text', 'itemLabel' => null,
            'metaFields' => [
                'step1_heading' => 'text', 'step1_sub' => 'textarea',
                'step2_heading' => 'text', 'step2_sub' => 'textarea',
                'step3_heading' => 'text', 'step3_sub' => 'textarea',
                'step4_heading' => 'text', 'step4_sub' => 'textarea',
                'confirmation_heading' => 'text', 'confirmation_message' => 'textarea',
                'emergency_notice' => 'textarea',
            ],
            'itemFields' => [],
        ],
        'booking_wizard_expect' => [
            'label' => 'Booking Wizard — "What to Expect"', 'itemLabel' => 'Item',
            'metaFields' => ['title' => 'text'],
            'itemFields' => ['icon' => 'text', 'title' => 'text', 'desc' => 'textarea'],
        ],
        'booking_wizard_contact' => [
            'label' => 'Booking Wizard — "Prefer to Call?"', 'itemLabel' => 'Contact Option',
            'metaFields' => ['title' => 'text'],
            'itemFields' => ['icon' => 'text', 'title' => 'text', 'desc' => 'text', 'href' => 'text'],
        ],
        'service_booking_wizard' => [
            'label' => 'Service Booking Wizard — Text', 'itemLabel' => null,
            'metaFields' => [
                'banner_title' => 'text', 'banner_sub' => 'text',
                'confirmation_heading' => 'text', 'confirmation_message' => 'textarea',
                'next_steps' => 'array',
            ],
            'itemFields' => [],
        ],
    ];

    public string $activeSection = 'about';

    public array $meta = [];

    public array $items = [];

    /** @var TemporaryUploadedFile[] keyed by item index, for item-level image fields */
    public array $itemImages = [];

    /** @var TemporaryUploadedFile[] keyed by meta field name, for section-level image fields */
    public array $metaImages = [];

    public function mount(): void
    {
        $this->loadSection($this->activeSection);
    }

    public function switchSection(string $key): void
    {
        $this->activeSection = $key;
        $this->itemImages = [];
        $this->metaImages = [];
        $this->loadSection($key);
    }

    private function schema(): array
    {
        return self::SECTIONS[$this->activeSection];
    }

    private function loadSection(string $key): void
    {
        $schema = self::SECTIONS[$key];
        $stored = HomeSection::content($key, ['meta' => [], 'items' => []]);

        $this->meta = $stored['meta'] ?? [];
        foreach (array_keys($schema['metaFields']) as $field) {
            if (($schema['metaFields'][$field] ?? null) === 'array') {
                $this->meta[$field] = implode("\n", $this->meta[$field] ?? []);
            } else {
                $this->meta[$field] ??= '';
            }
        }

        $items = $stored['items'] ?? [];
        foreach ($items as &$item) {
            foreach ($schema['itemFields'] as $field => $type) {
                if ($type === 'array') {
                    $item[$field] = implode("\n", $item[$field] ?? []);
                } elseif ($type === 'boolean') {
                    $item[$field] = (bool) ($item[$field] ?? false);
                } else {
                    $item[$field] ??= '';
                }
            }
        }
        unset($item);

        $this->items = $items;
    }

    public function addItem(): void
    {
        $schema = $this->schema();
        $blank = [];
        foreach ($schema['itemFields'] as $field => $type) {
            $blank[$field] = $type === 'boolean' ? false : '';
        }
        $this->items[] = $blank;
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        unset($this->itemImages[$index]);
    }

    public function moveItem(int $index, int $direction): void
    {
        $target = $index + $direction;
        if (! isset($this->items[$index]) || ! isset($this->items[$target])) {
            return;
        }
        [$this->items[$index], $this->items[$target]] = [$this->items[$target], $this->items[$index]];
    }

    public function updated($property, $value): void
    {
        if (preg_match('/^itemImages\.(\d+)\.(\w+)$/', $property, $m) && $value instanceof TemporaryUploadedFile) {
            $index = (int) $m[1];
            $field = $m[2];
            $path = $value->store('pages', 'public');
            $this->items[$index][$field] = '/storage/'.$path;
            unset($this->itemImages[$index][$field]);
        }

        if (preg_match('/^metaImages\.(\w+)$/', $property, $m) && $value instanceof TemporaryUploadedFile) {
            $field = $m[1];
            $path = $value->store('pages', 'public');
            $this->meta[$field] = '/storage/'.$path;
            unset($this->metaImages[$field]);
        }
    }

    public function save(): void
    {
        $schema = $this->schema();

        $meta = $this->meta;
        foreach (array_keys($schema['metaFields']) as $field) {
            if (($schema['metaFields'][$field] ?? null) === 'array') {
                $meta[$field] = array_values(array_filter(array_map('trim', explode("\n", (string) $meta[$field]))));
            }
        }

        $items = $this->items;
        foreach ($items as &$item) {
            foreach ($schema['itemFields'] as $field => $type) {
                if ($type === 'array') {
                    $item[$field] = array_values(array_filter(array_map('trim', explode("\n", (string) $item[$field]))));
                } elseif ($type === 'number') {
                    $item[$field] = is_numeric($item[$field]) ? $item[$field] + 0 : 0;
                } elseif ($type === 'boolean') {
                    $item[$field] = (bool) $item[$field];
                }
            }
        }
        unset($item);

        HomeSection::updateOrCreate(
            ['section_key' => $this->activeSection],
            ['content' => ['meta' => $meta, 'items' => $items]]
        );

        $this->dispatch('cart-toast', message: self::SECTIONS[$this->activeSection]['label'].' saved');
    }

    public function render()
    {
        return view('livewire.admin.home-content-manager', ['sections' => self::SECTIONS]);
    }
}
