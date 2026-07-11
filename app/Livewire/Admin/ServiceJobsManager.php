<?php

namespace App\Livewire\Admin;

use App\Events\ServiceJobStatusUpdated;
use App\Models\ServiceJob;
use App\Models\TechnicianProfile;
use Livewire\Component;

class ServiceJobsManager extends Component
{
    public string $statusFilter = '';

    public ?int $assigningId = null;

    public function startAssign(int $id): void
    {
        $this->assigningId = $id;
    }

    public function cancelAssign(): void
    {
        $this->assigningId = null;
    }

    public function assignTechnician(int $jobId, int $technicianId): void
    {
        $job = ServiceJob::findOrFail($jobId);
        $job->update([
            'technician_id' => $technicianId,
            'status' => ServiceJob::STATUS_ASSIGNED,
        ]);
        ServiceJobStatusUpdated::fire($job->fresh());
        $this->assigningId = null;
        $this->dispatch('cart-toast', message: 'Technician assigned');
    }

    public function updateStatus(int $id, string $status): void
    {
        $job = ServiceJob::findOrFail($id);
        $job->update(['status' => $status]);
        ServiceJobStatusUpdated::fire($job);
        $this->dispatch('cart-toast', message: 'Job status updated');
    }

    public function render()
    {
        $jobs = ServiceJob::with(['booking', 'technician'])
            ->when($this->statusFilter !== '', fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->get();

        $technicians = TechnicianProfile::where('status', TechnicianProfile::STATUS_ACTIVE)
            ->where('is_available', true)
            ->orderBy('first_name')
            ->get();

        $stats = [
            'total' => ServiceJob::count(),
            'active' => ServiceJob::whereNotIn('status', [ServiceJob::STATUS_COMPLETED, ServiceJob::STATUS_CANCELLED])->count(),
            'completed' => ServiceJob::where('status', ServiceJob::STATUS_COMPLETED)->count(),
        ];

        return view('livewire.admin.service-jobs-manager', compact('jobs', 'technicians', 'stats'));
    }
}
