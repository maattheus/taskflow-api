<?php

namespace App\Http\Controllers\Api\v1;

use App\DTOs\MoveTaskDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use App\DTOs\TaskDTO;
use App\UseCases\Task\MoveTaskToBoard;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(private TaskService $service)
    {
    }

    public function getAllByBoard($boardId)
    {
        $tasks = $this->service->getAllByBoard($boardId);
        return response()->json(['data' => $tasks], 200);
    }

    public function store(StoreTaskRequest $request)
    {
        $dto = TaskDTO::fromRequest($request);
        $task = $this->service->create($dto);
        return response()->json(['message' => 'Task created successfully', 'data' => $task], 201);
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $dto = TaskDTO::fromRequest($request);
        $task = $this->service->update($dto, $id);
        return response()->json(['message' => 'Task updated successfully', 'data' => $task], 200);
    }

    public function moveToBoard(Request $request, MoveTaskToBoard $useCase)
    {
        $dto = MoveTaskDTO::fromRequest($request);
        $task = $useCase->execute($dto);

        return response()->json([
            'message' => 'Task moved successfully',
            'data' => $task
        ], 200);
    }

}
