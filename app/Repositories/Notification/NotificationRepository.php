<?php

namespace App\Repositories\Notification;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationRepository implements NotificationInterface
{

    public function getByUser()
    {
        return Notification::where('user_id', Auth::id())->get();
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::findOrFail($notificationId);
        $notification->read = true;
        $notification->save();

        return $notification;
    }

    public function create($userId, $request)
    {
        $notification = new Notification();
        $notification->user_id = $userId;
        $notification->message = $request->message;
        $notification->task_id = $request->task_id ?? null;
        $notification->read = false;
        $notification->save();

        return $notification;
    }

}
