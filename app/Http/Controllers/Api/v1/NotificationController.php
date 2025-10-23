<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function getByUser()
    {
        try {

            $notifications = $this->notificationService->getByUser();

            return responseHandler([

                'message' => 'Notifications retrieved successfully',
                'status' => 200,
                'data' => $notifications

            ]);
        } catch (\Exception $e) {

            return responseHandler([

                'message' => 'There was an error creating the notification',
                'status' => 500,
                'data' => $e->getMessage()

            ]);
        }
    }

    public function markAsRead($id)
    {
        try {

            $notification = $this->notificationService->markAsRead($id);

            return responseHandler([

                'message' => 'Notification marked as read successfully',
                'status' => 200,
                'data' => $notification

            ]);
        } catch (\Exception $e) {

            $status = $e->getCode() === 403 ? 403 : 500;

            return responseHandler([
                'message' => $e->getMessage(),
                'status' => $status
            ]);
        }
    }

    public function create(Request $request)
    {
        try {

            $notification = $this->notificationService->create($request->user()->id, $request);

            return responseHandler([

                'message' => 'Notification created successfully',
                'status' => 201,
                'data' => $notification

            ]);
        } catch (\Exception $e) {

            return responseHandler([

                'message' => 'There was an error creating the notification',
                'status' => 500,
                'data' => $e->getMessage()

            ]);
        }
    }

    public function delete($id)
    {
        try {

            $this->notificationService->delete($id);

            return responseHandler([

                'message' => 'Notification deleted successfully',
                'status' => 200,
                'data' => null

            ]);
        } catch (\Exception $e) {

            $status = $e->getCode() === 403 ? 403 : 500;

            return responseHandler([

                'message' => $e->getMessage(),
                'status' => $status

            ]);
        }
    }

}
