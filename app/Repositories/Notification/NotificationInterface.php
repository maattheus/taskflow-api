<?php

namespace App\Repositories\Notification;

use App\DTOs\NotificationDTO;

interface NotificationInterface
{
    public function create(NotificationDTO $dto);
    public function getUnreadByUser(int $userId);
    public function markAsRead(int $id);
    public function getAllByUser(int $userId);
    public function delete(int $id);

}
