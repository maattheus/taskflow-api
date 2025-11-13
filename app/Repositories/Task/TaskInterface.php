<?php

namespace App\Repositories\Task;

use App\DTOs\TaskDTO;
use App\Models\Task;

interface TaskInterface
{
    public function getByBoard(int $boardId);
    public function createFromDto(TaskDTO $dto);
    public function updateFromDto(TaskDTO $dto, int $id);
    public function findById(int $id);
    public function save(Task $task);

}
