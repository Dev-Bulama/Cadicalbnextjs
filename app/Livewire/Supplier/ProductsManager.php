<?php

namespace App\Livewire\Supplier;

use App\Models\Supplier;
use App\Models\SupplierProduct;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ProductsManager extends Component
{
    #[Locked]
    public int $supplierId;

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $description = '';

    public string $category = '';

    public string $unit_price = '';

    public string $stock = '0';

    public bool $is_active = true;

    public function mount(): void
    {
        $this->supplierId = Supplier::where('user_id', auth()->id())->firstOrFail()->id;
    }

    public function create(): void
    {
        $this->reset(['editingId', 'name', 'description', 'category', 'unit_price']);
        $this->stock = '0';
        $this->is_active = true;
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $product = SupplierProduct::where('supplier_id', $this->supplierId)->findOrFail($id);
        $this->editingId = $product->id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->category = $product->category;
        $this->unit_price = (string) $product->unit_price;
        $this->stock = (string) $product->stock;
        $this->is_active = $product->is_active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'unit_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);
        $data['supplier_id'] = $this->supplierId;

        SupplierProduct::updateOrCreate(
            ['id' => $this->editingId, 'supplier_id' => $this->supplierId],
            $data
        );

        $this->showModal = false;
        $this->dispatch('cart-toast', message: 'Product saved');
    }

    public function delete(int $id): void
    {
        SupplierProduct::where('supplier_id', $this->supplierId)->where('id', $id)->delete();
        $this->dispatch('cart-toast', message: 'Product removed');
    }

    public function render()
    {
        $products = SupplierProduct::where('supplier_id', $this->supplierId)->latest()->get();

        return view('livewire.supplier.products-manager', compact('products'));
    }
}
