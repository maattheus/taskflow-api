<?php

namespace App\Services;

use App\Repositories\Task\TaskRepository;

class TaskService
{
    protected $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getAllByBoard($boardId)
    {
        return $this->taskRepository->getAllByBoard($boardId);
    }

    public function create($request)
    {
        return $this->taskRepository->create($request);
    }

    public function update($request, $id)
    {
        return $this->taskRepository->update($request, $id);
    }
}
