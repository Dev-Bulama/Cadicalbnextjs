<?php

namespace App\Livewire;

use App\Models\AppNotification;
use Livewire\Component;

class NotificationsCenter extends Component
{
    public string $filter = 'all';

    public function markAllRead(): void
    {
        AppNotification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
    }

    public function markRead(int $id): void
    {
        AppNotification::where('user_id', auth()->id())
            ->where('id', $id)
            ->update(['is_read' => true, 'read_at' => now()]);
    }

    public function render()
    {
        $notifications = AppNotification::where('user_id', auth()->id())
            ->when($this->filter === 'unread', fn ($q) => $q->where('is_read', false))
            ->latest()
            ->get();

        $unreadCount = AppNotification::where('user_id', auth()->id())->where('is_read', false)->count();

        return view('livewire.notifications-center', compact('notifications', 'unreadCount'));
    }
}
