<?php

namespace App\Repositories\Notification;

use App\DTOs\NotificationDTO;
use App\Models\Notification;

class NotificationRepository implements NotificationInterface
{
    public function create(NotificationDTO $dto)
    {
        return Notification::create([
            'user_id' => $dto->user_id,
            'task_id' => $dto->task_id,
            'message' => $dto->message,
            'read' => false,
        ]);
    }

    public function getUnreadByUser(int $userId)
    {
        return Notification::where('user_id', $userId)
            ->where('read', false)
            ->get();
    }

    public function markAsRead(int $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->read = true;
        $notification->save();

        return $notification;
    }

    public function getAllByUser(int $userId)
    {
        return Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function delete(int $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();
        return true;
    }
}
