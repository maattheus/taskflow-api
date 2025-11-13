<?php

namespace App\Repositories\Board;

use App\DTOs\BoardDTO;

interface BoardInterface
{
    public function getByProject($id);

    public function createFromDto(BoardDTO $data);

    public function updateFromDto(BoardDTO $data, int $id);

    public function findById(int $id);

}
