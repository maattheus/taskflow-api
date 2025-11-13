<?php

namespace App\Services;

use App\DTOs\BoardDTO;
use App\Repositories\Board\BoardInterface;
use Illuminate\Support\Facades\Log;

class BoardService
{

    public function __construct(private BoardInterface $repository)
    {
    }

    public function create(BoardDTO $dto)
    {
        try {
            return $this->repository->createFromDto($dto);
        } catch (\Throwable $e) {
            Log::channel('board')->error('Erro ao criar board', [
                'message' => $e->getMessage(),
                'dto' => $dto,
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e; // re-lança o erro pro handler tratar
        }
    }

    public function update(BoardDTO $dto, int $id)
    {
        try {
            return $this->repository->updateFromDto($dto, $id);
        } catch (\Throwable $e) {
            Log::channel('board')->error('Erro ao atualizar board', [
                'message' => $e->getMessage(),
                'board_id' => $id,
                'user_id' => auth()->id(),
            ]);

            throw $e;
        }
    }

    public function getByProject(int $projectId)
    {
        return $this->repository->getByProject($projectId);
    }
}
