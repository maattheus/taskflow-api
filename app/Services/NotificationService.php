<?php

namespace App\Services;

use App\DTOs\NotificationDTO;
use App\Repositories\Notification\NotificationInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NotificationService
{
    public function __construct(private NotificationInterface $repository)
    {
    }

    public function create(NotificationDTO $dto)
    {
        try {
            return $this->repository->create($dto);
        } catch (\Throwable $e) {
            Log::channel('notification')->error('Error creating notification.', [
                'trace_id' => Str::uuid()->toString(),
                'message' => $e->getMessage(),
                'task_id' => $dto->task_id,
                'user_id' => $dto->user_id,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function getUnread(int $userId)
    {
        return $this->repository->getUnreadByUser($userId);
    }

    public function markAsRead(int $id)
    {
        return $this->repository->markAsRead($id);
    }

    public function getAllByUser(int $userId)
    {
        try {
            return $this->repository->getAllByUser($userId);
        } catch (\Throwable $e) {
            Log::channel('notification')->error('Error fetching notifications.', [
                'trace_id' => Str::uuid()->toString(),
                'message' => $e->getMessage(),
                'user_id' => $userId,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function delete(int $id)
    {
        try {
            return $this->repository->delete($id);
        } catch (\Throwable $e) {
            Log::channel('notification')->error('Error deleting notification.', [
                'trace_id' => Str::uuid()->toString(),
                'message' => $e->getMessage(),
                'notification_id' => $id,
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

}
