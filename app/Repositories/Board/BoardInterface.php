<?php

namespace App\Repositories\Board;

interface BoardInterface
{
    public function getAllByProject($id);

    public function create($data);
}
