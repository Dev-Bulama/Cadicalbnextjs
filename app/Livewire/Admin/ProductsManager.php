<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ProductsManager extends Component
{
    use WithFileUploads;
    use WithPagination;

    public const CATEGORIES = [
        'Imaging', 'Diagnostics', 'ICU', 'Surgery', 'Laboratory',
        'Consumables', 'Monitoring', 'Dental', 'Rehabilitation',
    ];

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    public string $description = '';

    #[Validate('required|numeric|min:0')]
    public string $price = '';

    #[Validate('required|integer|min:0')]
    public string $stock = '';

    #[Validate('required|string|max:50')]
    public string $sku = '';

    #[Validate('required|string')]
    public string $category = '';

    public $image = null;

    public ?string $existingImage = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->reset(['editingId', 'name', 'description', 'price', 'stock', 'sku', 'category', 'image', 'existingImage']);
        $this->resetValidation();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $product = Product::findOrFail($id);
        $this->editingId = $product->id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = (string) $product->price;
        $this->stock = (string) $product->stock;
        $this->sku = $product->sku;
        $this->category = $product->category;
        $this->existingImage = $product->image;
        $this->image = null;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $imagePath = $this->existingImage;
        if ($this->image) {
            $imagePath = $this->image->store('product-images', 'public');
        }

        Product::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'stock' => $this->stock,
                'sku' => $this->sku,
                'category' => $this->category,
                'image' => $imagePath,
            ]
        );

        $this->showModal = false;
        $this->dispatch('cart-toast', message: $this->editingId ? 'Product updated' : 'Product created');
    }

    public function delete(int $id): void
    {
        Product::findOrFail($id)->delete();
        $this->dispatch('cart-toast', message: 'Product deleted');
    }

    public function render()
    {
        $products = Product::query()
            ->when($this->search !== '', fn ($q) => $q->where('name', 'like', '%'.$this->search.'%')->orWhere('sku', 'like', '%'.$this->search.'%'))
            ->orderByDesc('id')
            ->paginate(15);

        return view('livewire.admin.products-manager', compact('products'));
    }
}
