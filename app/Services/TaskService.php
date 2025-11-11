<?php

namespace App\Services;

use App\DTOs\TaskDTO;
use App\Repositories\Task\TaskInterface;

class TaskService
{
    public function __construct(private TaskInterface $repository)
    {
    }

    public function getAllByBoard(int $boardId)
    {
        return $this->repository->getAllByBoard($boardId);
    }

    public function create(TaskDTO $dto)
    {
        return $this->repository->createFromDto($dto);
    }

    public function update(TaskDTO $dto, int $id)
    {
        return $this->repository->updateFromDto($dto, $id);
    }
}
