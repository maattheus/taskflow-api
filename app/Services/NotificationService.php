<?php

namespace App\Services;

use App\Repositories\Notification\NotificationRepository;
use Auth;
use Exception;

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

        $notification = $this->notificationRepository->find($notificationId);

        if (!$notification) {
            throw new Exception('Notification not found');
        }

        if ($notification->user_id !== Auth::id()) {
            throw new Exception('Forbidden', 403);
        }

        return $this->notificationRepository->markAsRead($notification);
    }

    public function create($userId, $request)
    {
        return $this->notificationRepository->create($userId, $request);
    }

    public function delete($notificationId)
    {

        $notification = $this->notificationRepository->find($notificationId);

        if (!$notification) {
            throw new Exception('Notification not found');
        }

        if ($notification->user_id !== Auth::id()) {
            throw new Exception('Forbidden', 403);
        }

        return $this->notificationRepository->delete($notificationId);
    }

}
