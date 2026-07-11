<?php

namespace App\Livewire\Technician;

use App\Events\ServiceJobStatusUpdated;
use App\Models\ServiceJob;
use App\Models\TechnicianProfile;
use Livewire\Attributes\Locked;
use Livewire\Component;

class JobsBoard extends Component
{
    #[Locked]
    public int $technicianId;

    public string $tab = 'active';

    private const ACTIVE_STATUSES = [
        ServiceJob::STATUS_ASSIGNED, ServiceJob::STATUS_ACCEPTED, ServiceJob::STATUS_EN_ROUTE,
        ServiceJob::STATUS_ON_SITE, ServiceJob::STATUS_IN_PROGRESS, ServiceJob::STATUS_WAITING_PARTS,
    ];

    private const NEXT_STATUS = [
        ServiceJob::STATUS_ACCEPTED => ServiceJob::STATUS_EN_ROUTE,
        ServiceJob::STATUS_EN_ROUTE => ServiceJob::STATUS_ON_SITE,
        ServiceJob::STATUS_ON_SITE => ServiceJob::STATUS_IN_PROGRESS,
        ServiceJob::STATUS_IN_PROGRESS => ServiceJob::STATUS_COMPLETED,
    ];

    public function mount(): void
    {
        $this->technicianId = TechnicianProfile::where('user_id', auth()->id())->firstOrFail()->id;
    }

    public function accept(int $jobId): void
    {
        $this->updateJob($jobId, ServiceJob::STATUS_ACCEPTED, ['started_at' => null]);
    }

    public function decline(int $jobId): void
    {
        $this->updateJob($jobId, ServiceJob::STATUS_REJECTED);
    }

    public function advance(int $jobId): void
    {
        $job = ServiceJob::where('technician_id', $this->technicianId)->findOrFail($jobId);
        $next = self::NEXT_STATUS[$job->status] ?? null;
        if (! $next) {
            return;
        }

        $extra = [];
        if ($next === ServiceJob::STATUS_IN_PROGRESS) {
            $extra['started_at'] = now();
        }
        if ($next === ServiceJob::STATUS_COMPLETED) {
            $extra['completed_at'] = now();
        }

        $this->updateJob($jobId, $next, $extra);
    }

    private function updateJob(int $jobId, string $status, array $extra = []): void
    {
        $job = ServiceJob::where('technician_id', $this->technicianId)->findOrFail($jobId);
        $job->update(array_merge(['status' => $status], $extra));
        ServiceJobStatusUpdated::fire($job);

        $this->dispatch('cart-toast', message: 'Job updated');
    }

    public function render()
    {
        $jobs = ServiceJob::with('booking.user')
            ->where('technician_id', $this->technicianId)
            ->latest()
            ->get();

        $active = $jobs->whereIn('status', self::ACTIVE_STATUSES);
        $completed = $jobs->where('status', ServiceJob::STATUS_COMPLETED);

        return view('livewire.technician.jobs-board', compact('active', 'completed'));
    }
}
