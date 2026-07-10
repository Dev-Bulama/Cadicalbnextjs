<?php

namespace App\Livewire\Technician;

use App\Models\ServiceJob;
use App\Models\TechnicianProfile;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ScheduleCalendar extends Component
{
    #[Locked]
    public int $technicianId;

    public int $year;

    public int $month;

    public int $selectedDay;

    public function mount(): void
    {
        $this->technicianId = TechnicianProfile::where('user_id', auth()->id())->firstOrFail()->id;
        $this->year = (int) now()->year;
        $this->month = (int) now()->month;
        $this->selectedDay = (int) now()->day;
    }

    public function prevMonth(): void
    {
        if ($this->month === 1) {
            $this->month = 12;
            $this->year--;
        } else {
            $this->month--;
        }
        $this->selectedDay = 1;
    }

    public function nextMonth(): void
    {
        if ($this->month === 12) {
            $this->month = 1;
            $this->year++;
        } else {
            $this->month++;
        }
        $this->selectedDay = 1;
    }

    public function selectDay(int $day): void
    {
        $this->selectedDay = $day;
    }

    public function render()
    {
        $monthStart = now()->setDate($this->year, $this->month, 1)->startOfDay();
        $monthEnd = $monthStart->copy()->endOfMonth();

        $jobs = ServiceJob::with('booking')
            ->where('technician_id', $this->technicianId)
            ->whereBetween('scheduled_at', [$monthStart, $monthEnd])
            ->get()
            ->groupBy(fn ($job) => $job->scheduled_at->format('Y-m-d'));

        $daysInMonth = $monthStart->daysInMonth;
        $firstDayOfWeek = $monthStart->dayOfWeek;

        $selectedKey = sprintf('%04d-%02d-%02d', $this->year, $this->month, $this->selectedDay);
        $selectedJobs = $jobs->get($selectedKey, collect());

        return view('livewire.technician.schedule-calendar', compact('jobs', 'daysInMonth', 'firstDayOfWeek', 'selectedJobs', 'monthStart'));
    }
}
