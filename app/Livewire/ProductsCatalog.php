<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Attributes\Url;
use Livewire\Component;

class ProductsCatalog extends Component
{
    private const MAX_PRICE = 5000000;

    #[Url(as: 'search', history: true)]
    public string $search = '';

    #[Url(as: 'category', history: true)]
    public ?string $category = null;

    public int $maxPrice = self::MAX_PRICE;

    public function getMaxPriceLimitProperty(): int
    {
        return self::MAX_PRICE;
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->category = null;
        $this->maxPrice = self::MAX_PRICE;
    }

    public function getHasActiveFiltersProperty(): bool
    {
        return $this->search !== '' || $this->category !== null || $this->maxPrice < self::MAX_PRICE;
    }

    public function getProductsProperty()
    {
        return Product::query()
            ->when($this->search !== '', fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->when($this->category, fn ($q) => $q->where('category', $this->category))
            ->when($this->maxPrice < self::MAX_PRICE, fn ($q) => $q->where('price', '<=', $this->maxPrice))
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.products-catalog', [
            'products' => $this->products,
        ]);
    }
}
