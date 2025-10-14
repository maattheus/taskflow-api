<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function getAllByBoard($boardId)
    {
        try {
            $tasks = $this->taskService->getAllByBoard($boardId);

            return responseHandler([
                'message' => 'Tasks fetched successfully',
                'status' => 200,
                'data' => $tasks,
            ]);
        } catch (\Exception $e) {
            return responseHandler([
                'message' => 'Error fetching tasks',
                'status' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function create(Request $request)
    {
        try {
            $task = $this->taskService->create($request);

            return responseHandler([
                'message' => 'Task created successfully',
                'status' => 201,
                'data' => $task,
            ]);
        } catch (\Exception $e) {
            return responseHandler([
                'message' => 'Error creating task',
                'status' => 500,
                'data' => $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $task = $this->taskService->update($request, $id);

            return responseHandler([
                'message' => 'Task updated successfully',
                'status' => 200,
                'data' => $task,
            ]);
        } catch (\Exception $e) {
            return responseHandler([
                'message' => 'Error updating task',
                'status' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
