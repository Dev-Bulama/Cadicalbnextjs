<?php

namespace App\Livewire\Admin;

use App\Models\Service;
use Livewire\Component;

class ServicesManager extends Component
{
    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $description = '';

    public string $category = 'CONSULTATIONS';

    public string $icon = '';

    public bool $is_active = true;

    public int $order = 0;

    public array $categories = [
        Service::CATEGORY_CONSULTATIONS, Service::CATEGORY_PHARMACEUTICALS, Service::CATEGORY_SURGICAL_EQUIPMENT,
        Service::CATEGORY_DIAGNOSTICS, Service::CATEGORY_REHABILITATION, Service::CATEGORY_EMERGENCY,
        Service::CATEGORY_COSMETICS, Service::CATEGORY_REFERRALS,
    ];

    public function create(): void
    {
        $this->reset(['editingId', 'name', 'description', 'icon', 'order']);
        $this->category = 'CONSULTATIONS';
        $this->is_active = true;
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $service = Service::findOrFail($id);
        $this->editingId = $service->id;
        $this->name = $service->name;
        $this->description = $service->description;
        $this->category = $service->category;
        $this->icon = (string) $service->icon;
        $this->is_active = $service->is_active;
        $this->order = $service->order;
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
        ]);

        Service::updateOrCreate(['id' => $this->editingId], $data);

        $this->showModal = false;
        $this->dispatch('cart-toast', message: 'Service saved');
    }

    public function toggleActive(int $id): void
    {
        $service = Service::findOrFail($id);
        $service->update(['is_active' => ! $service->is_active]);
    }

    public function delete(int $id): void
    {
        Service::whereKey($id)->delete();
        $this->dispatch('cart-toast', message: 'Service deleted');
    }

    public function render()
    {
        $services = Service::orderBy('order')->orderBy('name')->get();

        return view('livewire.admin.services-manager', compact('services'));
    }
}
