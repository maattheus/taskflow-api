<?php

namespace App\Repositories\Notification;

interface NotificationInterface
{

    public function getByUser();

    public function markAsRead($notificationId);

    public function create($userId, $request);

}
