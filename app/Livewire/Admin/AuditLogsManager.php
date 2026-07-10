<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use Livewire\Component;
use Livewire\WithPagination;

class AuditLogsManager extends Component
{
    use WithPagination;

    public string $search = '';

    public string $actionFilter = '';

    public string $entityFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $logs = AuditLog::with('user')
            ->when($this->search !== '', function ($q) {
                $q->where('user_email', 'like', '%'.$this->search.'%')
                    ->orWhere('entity_id', 'like', '%'.$this->search.'%');
            })
            ->when($this->actionFilter !== '', fn ($q) => $q->where('action', $this->actionFilter))
            ->when($this->entityFilter !== '', fn ($q) => $q->where('entity', $this->entityFilter))
            ->latest()
            ->paginate(20);

        $entities = AuditLog::query()->distinct()->pluck('entity');
        $actions = AuditLog::query()->distinct()->pluck('action');

        return view('livewire.admin.audit-logs-manager', compact('logs', 'entities', 'actions'));
    }
}
