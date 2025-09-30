<?php

namespace App\Services;

use App\Repositories\Board\BoardRepository;

class BoardService
{
    protected $boardRepository;

    public function __construct(BoardRepository $boardRepository)
    {
        $this->boardRepository = $boardRepository;
    }

    public function getAllByProject($id)
    {
        return $this->boardRepository->getAllByProject($id);
    }

    public function create($data)
    {   

        return $this->boardRepository->create($data);

    }
    public function update($data, $id)
    {   
        
        return $this->boardRepository->update($data, $id);

    }
}
