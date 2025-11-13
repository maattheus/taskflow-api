<?php

namespace App\Repositories\Task;

use App\DTOs\TaskDTO;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskRepository implements TaskInterface
{
    public function getByBoard(int $boardId)
    {
        return Task::where('board_id', $boardId)->get();
    }

    public function createFromDto(TaskDTO $dto)
    {
        return Task::create([
            'title' => $dto->title,
            'description' => $dto->description,
            'board_id' => $dto->board_id,
            'created_by' => Auth::id(),
            'assigned_to' => $dto->assigned_to,
            'priority' => $dto->priority,
            'due_date' => $dto->due_date,
        ]);
    }

    public function updateFromDto(TaskDTO $dto, int $id)
    {
        $task = Task::findOrFail($id);

        $updateData = array_filter([
            'title' => $dto->title,
            'description' => $dto->description,
            'board_id' => $dto->board_id,
            'assigned_to' => $dto->assigned_to,
            'priority' => $dto->priority,
            'due_date' => $dto->due_date,
        ], fn($value) => !is_null($value));

        $task->update($updateData);

        return $task->fresh();
    }

    public function findById(int $id): ?Task
    {
        return Task::find($id);
    }

    public function save(Task $task): Task
    {
        $task->save();
        return $task;
    }
}
