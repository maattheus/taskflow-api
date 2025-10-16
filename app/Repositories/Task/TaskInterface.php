<?php

namespace App\Repositories\Task;

interface TaskInterface
{
    public function getAllByBoard($boardId);

    public function create($params);

    public function update($params, int $id);
}
