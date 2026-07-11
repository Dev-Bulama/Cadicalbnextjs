<?php

namespace App\Livewire\Admin;

use App\Models\MaintenanceSchedule;
use Livewire\Component;

class MaintenanceManager extends Component
{
    public string $search = '';

    public string $filter = 'all';

    public function markCompleted(int $id): void
    {
        $schedule = MaintenanceSchedule::findOrFail($id);
        $schedule->history()->create([
            'completed_at' => now(),
            'notes' => 'Marked completed from admin console',
        ]);

        $next = match ($schedule->frequency) {
            MaintenanceSchedule::FREQUENCY_WEEKLY => now()->addWeek(),
            MaintenanceSchedule::FREQUENCY_MONTHLY => now()->addMonth(),
            MaintenanceSchedule::FREQUENCY_QUARTERLY => now()->addMonths(3),
            MaintenanceSchedule::FREQUENCY_BIANNUAL => now()->addMonths(6),
            default => now()->addYear(),
        };

        $schedule->update(['last_completed_at' => now(), 'next_due_date' => $next]);
        $this->dispatch('cart-toast', message: 'Maintenance marked complete');
    }

    public function render()
    {
        $query = MaintenanceSchedule::with('technician')
            ->when($this->search !== '', function ($q) {
                $q->where('equipment_name', 'like', '%'.$this->search.'%')
                    ->orWhere('schedule_code', 'like', '%'.$this->search.'%');
            });

        if ($this->filter === 'overdue') {
            $query->where('is_active', true)->where('next_due_date', '<', now());
        } elseif ($this->filter === 'due_soon') {
            $query->where('is_active', true)->whereBetween('next_due_date', [now(), now()->addDays(7)]);
        }

        $schedules = $query->orderBy('next_due_date')->get();

        $stats = [
            'total' => MaintenanceSchedule::count(),
            'overdue' => MaintenanceSchedule::where('is_active', true)->where('next_due_date', '<', now())->count(),
            'dueSoon' => MaintenanceSchedule::where('is_active', true)->whereBetween('next_due_date', [now(), now()->addDays(7)])->count(),
        ];

        return view('livewire.admin.maintenance-manager', compact('schedules', 'stats'));
    }
}
