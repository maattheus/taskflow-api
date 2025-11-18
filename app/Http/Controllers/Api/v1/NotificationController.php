<?php

namespace App\Http\Controllers\Api\v1;

use App\DTOs\NotificationDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNotificationRequest;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(private NotificationService $service)
    {
    }

    public function store(StoreNotificationRequest $request)
    {
        $dto = NotificationDTO::fromRequest($request);
        $notification = $this->service->create($dto);

        return response()->json(['message' => 'Notification created', 'data' => $notification], 201);
    }

    public function getUnread()
    {
        $notifications = $this->service->getUnread(auth()->id());

        return response()->json(['message' => 'Unread notifications fetched', 'data' => $notifications], 200);
    }

    public function markAsRead($id)
    {
        $notification = $this->service->markAsRead($id);

        return response()->json(['message' => 'Notification marked as read', 'data' => $notification], 200);
    }

    public function getAll()
    {
        $notifications = $this->service->getAllByUser(auth()->id());

        return response()->json([
            'message' => 'Notifications fetched successfully.',
            'data' => $notifications
        ], 200);
    }

    public function destroy($id)
    {
        $deleted = $this->service->delete($id);

        return response()->json([
            'message' => 'Notification deleted successfully.',
            'deleted' => $deleted
        ], 200);
    }
}
