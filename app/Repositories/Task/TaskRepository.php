<?php

namespace App\Repositories\Task;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskRepository implements TaskInterface
{

    public function getAllByBoard($boardId)
    {
        return Task::where('board_id', $boardId)->get();
    }

    public function create($params)
    {
        $params = (object) $params;

        $task = new Task();

        $task->title = $params->title;
        $task->description  = $params->description;
        $task->board_id   = $params->board_id;
        $task->created_by  = Auth::id();
        $task->assigned_to = $params->assigned_to ?? null;
        $task->priority    = $params->priority ?? 2; // Default to Medium

        $task->save();

        return $task;
    }

    public function update($params, int $id)
    {

        $params = (object) $params;

        $task = Task::findOrFail($id);

        $task->title       = $params->title ?? $task->title;
        $task->description = $params->description ?? $task->description;
        $task->board_id    = $params->board_id ?? $task->board_id;
        $task->assigned_to = $params->assigned_to ?? $task->assigned_to;
        $task->priority    = $params->priority ?? $task->priority;

        $task->save();

        return $task;
    }
}
