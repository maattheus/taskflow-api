<?php

namespace App\Repositories\Task;

use App\DTOs\TaskDTO;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskRepository implements TaskInterface
{
    public function getAllByBoard(int $boardId)
    {
        return Task::where('board_id', $boardId)->get();
    }

    public function createFromDto(TaskDTO $dto)
    {
        $task = new Task();
        $task->fill([
            'title' => $dto->title,
            'description' => $dto->description,
            'board_id' => $dto->board_id,
            'created_by' => Auth::id(),
            'assigned_to' => $dto->assigned_to,
            'priority' => $dto->priority ?? 'medium',
            'due_date' => $dto->due_date,
        ]);
        $task->save();
        return $task;
    }

    public function updateFromDto(TaskDTO $dto, int $id)
    {
        $task = Task::findOrFail($id);
        $task->fill([
            'title' => $dto->title ?? $task->title,
            'description' => $dto->description ?? $task->description,
            'board_id' => $dto->board_id ?? $task->board_id,
            'assigned_to' => $dto->assigned_to ?? $task->assigned_to,
            'priority' => $dto->priority ?? $task->priority,
            'due_date' => $dto->due_date ?? $task->due_date,
        ]);
        $task->save();
        return $task;
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
