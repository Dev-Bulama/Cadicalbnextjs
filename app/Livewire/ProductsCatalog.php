<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Attributes\Url;
use Livewire\Component;

class ProductsCatalog extends Component
{
    private const DEFAULT_MAX_PRICE = 20_000_000;

    #[Url(as: 'search', history: true)]
    public string $search = '';

    #[Url(as: 'category', history: true)]
    public ?string $category = null;

    public int $maxPrice;

    public int $maxPriceLimit;

    public function mount(): void
    {
        $this->maxPriceLimit = (int) (config('shop.max_price') ?: self::DEFAULT_MAX_PRICE);
        $this->maxPrice = $this->maxPriceLimit;
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->category = null;
        $this->maxPrice = $this->maxPriceLimit;
    }

    public function getHasActiveFiltersProperty(): bool
    {
        return $this->search !== '' || $this->category !== null || $this->maxPrice < $this->maxPriceLimit;
    }

    public function getProductsProperty()
    {
        return Product::query()
            ->when($this->search !== '', fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->when($this->category, fn ($q) => $q->where('category', $this->category))
            ->when($this->maxPrice < $this->maxPriceLimit, fn ($q) => $q->where('price', '<=', $this->maxPrice))
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
