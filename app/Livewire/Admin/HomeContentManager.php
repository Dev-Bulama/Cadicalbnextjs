<?php

namespace App\Livewire\Admin;

use App\Models\HomeSection;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class HomeContentManager extends Component
{
    use WithFileUploads;

    /**
     * Field-type schema per section. 'array' fields are edited as
     * newline-separated text and converted to/from a real array on
     * load/save. 'image' fields get a companion file upload input.
     */
    public const SECTIONS = [
        'hero' => [
            'label' => 'Hero Slider', 'itemLabel' => 'Slide',
            'metaFields' => [],
            'itemFields' => [
                'badge' => 'text', 'headline' => 'textarea', 'sub' => 'textarea',
                'cta1_label' => 'text', 'cta1_href' => 'text',
                'cta2_label' => 'text', 'cta2_href' => 'text',
                'image' => 'image', 'gradient' => 'text',
            ],
        ],
        'categories' => [
            'label' => 'Categories', 'itemLabel' => 'Category',
            'metaFields' => ['eyebrow' => 'text', 'heading' => 'text'],
            'itemFields' => ['icon' => 'text', 'name' => 'text', 'sub' => 'text', 'href' => 'text', 'color' => 'text'],
        ],
        'portals' => [
            'label' => 'Portals', 'itemLabel' => 'Portal',
            'metaFields' => ['eyebrow' => 'text', 'heading' => 'text', 'sub' => 'textarea'],
            'itemFields' => ['icon' => 'text', 'badge' => 'text', 'title' => 'text', 'sub' => 'textarea', 'color' => 'text', 'iconColor' => 'text', 'features' => 'array', 'cta_label' => 'text', 'cta_href' => 'text', 'accent' => 'text', 'popular' => 'boolean'],
        ],
        'featured_products' => [
            'label' => 'Featured Products (heading only — products come from the catalog)', 'itemLabel' => null,
            'metaFields' => ['eyebrow' => 'text', 'heading' => 'text'],
            'itemFields' => [],
        ],
        'why' => [
            'label' => 'Why Cadical', 'itemLabel' => 'Pillar',
            'metaFields' => ['eyebrow' => 'text', 'heading' => 'text', 'heading_accent' => 'text', 'paragraph' => 'textarea', 'image' => 'image', 'badge_title' => 'text', 'badge_sub' => 'text'],
            'itemFields' => ['icon' => 'text', 'title' => 'text', 'desc' => 'textarea', 'color' => 'text'],
        ],
        'stats' => [
            'label' => 'Stats', 'itemLabel' => 'Stat',
            'metaFields' => [],
            'itemFields' => ['value' => 'number', 'suffix' => 'text', 'label' => 'text', 'sub' => 'text'],
        ],
        'services' => [
            'label' => 'Services', 'itemLabel' => 'Service',
            'metaFields' => ['eyebrow' => 'text', 'heading' => 'text', 'sub' => 'textarea', 'cta_label' => 'text'],
            'itemFields' => ['icon' => 'text', 'title' => 'text', 'desc' => 'textarea', 'tags' => 'array', 'color' => 'text', 'cta' => 'text'],
        ],
        'tracking_showcase' => [
            'label' => 'Tracking Showcase', 'itemLabel' => 'Stage',
            'metaFields' => ['eyebrow' => 'text', 'heading' => 'text', 'paragraph' => 'textarea', 'bullets' => 'array', 'cta_label' => 'text'],
            'itemFields' => ['icon' => 'text', 'label' => 'text', 'desc' => 'text'],
        ],
        'process' => [
            'label' => 'Process', 'itemLabel' => 'Step',
            'metaFields' => ['eyebrow' => 'text', 'heading' => 'text', 'sub' => 'textarea'],
            'itemFields' => ['n' => 'text', 'title' => 'text', 'desc' => 'textarea'],
        ],
        'testimonials' => [
            'label' => 'Testimonials', 'itemLabel' => 'Testimonial',
            'metaFields' => ['eyebrow' => 'text', 'heading' => 'text'],
            'itemFields' => ['name' => 'text', 'role' => 'text', 'org' => 'text', 'text' => 'textarea', 'rating' => 'number'],
        ],
        'cta' => [
            'label' => 'Call To Action', 'itemLabel' => null,
            'metaFields' => ['badge' => 'text', 'headline' => 'textarea', 'sub' => 'textarea', 'button1_label' => 'text', 'button1_href' => 'text', 'button2_label' => 'text', 'button2_href' => 'text'],
            'itemFields' => [],
        ],
        'compliance' => [
            'label' => 'Compliance', 'itemLabel' => 'Item',
            'metaFields' => ['eyebrow' => 'text', 'heading' => 'text', 'heading_accent' => 'text', 'sub' => 'textarea'],
            'itemFields' => ['tag' => 'text', 'title' => 'text', 'detail' => 'text'],
        ],
        'coverage' => [
            'label' => 'Coverage', 'itemLabel' => 'City',
            'metaFields' => ['eyebrow' => 'text', 'heading' => 'text', 'heading_accent' => 'text', 'paragraph' => 'textarea', 'image' => 'image'],
            'itemFields' => ['city' => 'text', 'time' => 'text'],
        ],
    ];

    public string $activeSection = 'hero';

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
            $this->meta[$field] ??= '';
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
        if (preg_match('/^itemImages\.(\d+)$/', $property, $m) && $value instanceof TemporaryUploadedFile) {
            $index = (int) $m[1];
            $path = $value->store('home', 'public');
            $this->items[$index]['image'] = '/storage/'.$path;
            unset($this->itemImages[$index]);
        }

        if (preg_match('/^metaImages\.(\w+)$/', $property, $m) && $value instanceof TemporaryUploadedFile) {
            $field = $m[1];
            $path = $value->store('home', 'public');
            $this->meta[$field] = '/storage/'.$path;
            unset($this->metaImages[$field]);
        }
    }

    public function save(): void
    {
        $schema = $this->schema();

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
            ['content' => ['meta' => $this->meta, 'items' => $items]]
        );

        $this->dispatch('cart-toast', message: self::SECTIONS[$this->activeSection]['label'].' saved');
    }

    public function render()
    {
        return view('livewire.admin.home-content-manager', ['sections' => self::SECTIONS]);
    }
}
