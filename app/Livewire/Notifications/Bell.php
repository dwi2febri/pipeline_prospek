<?php

namespace App\Livewire\Notifications;

use Livewire\Component;
use App\Models\ProspectNotification;

class Bell extends Component
{
    public bool $open = false;

    public function toggle(): void
    {
        $this->open = !$this->open;
    }

    public function markAsRead(int $id): void
    {
        $notif = ProspectNotification::query()
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if ($notif && !$notif->read_at) {
            $notif->update([
                'read_at' => now(),
            ]);
        }
    }

    public function markAllAsRead(): void
    {
        ProspectNotification::query()
            ->where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);
    }

    public function render()
    {
        $notifications = ProspectNotification::query()
            ->where('user_id', auth()->id())
            ->latest('id')
            ->limit(10)
            ->get();

        $unreadCount = ProspectNotification::query()
            ->where('user_id', auth()->id())
            ->whereNull('read_at')
            ->count();

        return view('livewire.notifications.bell', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }
}
