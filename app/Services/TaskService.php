<?php

namespace App\Services;

use App\DTOs\TaskDTO;
use App\Repositories\Task\TaskInterface;
use Illuminate\Support\Facades\Log;


class TaskService
{
    public function __construct(private TaskInterface $repository)
    {
    }

    public function getByBoard(int $boardId)
    {
        try {

            return $this->repository->getByBoard($boardId);

        } catch (\Throwable $e) {
            Log::channel('task')->error('Error retrieving tasks from the board.', [
                'message' => $e->getMessage(),
                'board_id' => $boardId,
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }

    }

    public function create(TaskDTO $dto)
    {
        try {
            return $this->repository->createFromDto($dto);
        } catch (\Throwable $e) {
            Log::channel('task')->error('Error creating task', [
                'message' => $e->getMessage(),
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function update(TaskDTO $dto, int $id)
    {
        try {
            return $this->repository->updateFromDto($dto, $id);
        } catch (\Throwable $e) {
            Log::channel('task')->error('Error updating task', [
                'message' => $e->getMessage(),
                'task_id' => $id,
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }

    }
}
