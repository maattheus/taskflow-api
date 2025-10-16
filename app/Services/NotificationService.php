<?php

namespace App\Services;

use App\Repositories\Notification\NotificationRepository;

class NotificationService
{
    protected $notificationRepository;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }


    public function getByUser()
    {
        return $this->notificationRepository->getByUser();
    }

    public function markAsRead($notificationId)
    {
        return $this->notificationRepository->markAsRead($notificationId);
    }

    public function create($userId, $request)
    {
        return $this->notificationRepository->create($userId, $request);
    }

}
