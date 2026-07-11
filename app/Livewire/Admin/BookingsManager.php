<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use Livewire\Component;
use Livewire\WithPagination;

class BookingsManager extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $selectedId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function select(int $id): void
    {
        $this->selectedId = $id;
    }

    public function closeDrawer(): void
    {
        $this->selectedId = null;
    }

    public function updateStatus(int $id, string $status): void
    {
        Booking::whereKey($id)->update(['status' => $status]);
        $this->dispatch('cart-toast', message: "Booking marked {$status}");
        $this->selectedId = null;
    }

    public function getSelectedProperty(): ?Booking
    {
        return $this->selectedId ? Booking::find($this->selectedId) : null;
    }

    public function render()
    {
        $bookings = Booking::query()
            ->when($this->search !== '', function ($q) {
                $q->where('first_name', 'like', '%'.$this->search.'%')
                    ->orWhere('last_name', 'like', '%'.$this->search.'%')
                    ->orWhere('ref', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.bookings-manager', compact('bookings'));
    }
}
