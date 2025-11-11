<?php

namespace App\UseCases\Task;

use App\DTOs\MoveTaskDTO;

use App\Exceptions\InvalidBoardMoveException;
use App\Repositories\Board\BoardInterface;
use App\Repositories\Task\TaskInterface;
use Illuminate\Support\Facades\Log;

class MoveTaskToBoard
{
    public function __construct(
        private TaskInterface $taskRepository,
        private BoardInterface $boardRepository
    ) {
    }

    public function execute(MoveTaskDTO $dto)
    {
        // 1️⃣ Verifica se a task existe
        $task = $this->taskRepository->findById($dto->task_id);
        if (!$task) {
            throw new InvalidBoardMoveException("Task not found.");
        }

        // 2️⃣ Verifica se os boards são válidos
        $fromBoard = $this->boardRepository->findById($dto->from_board_id);
        $toBoard = $this->boardRepository->findById($dto->to_board_id);

        if (!$fromBoard || !$toBoard) {
            throw new InvalidBoardMoveException("One or both boards not found.");
        }

        // 3️⃣ Valida regra de negócio
        if ($task->board_id !== $dto->from_board_id) {
            throw new InvalidBoardMoveException("Task does not belong to the source board.");
        }

        // 4️⃣ Atualiza o board
        $task->board_id = $dto->to_board_id;
        $this->taskRepository->save($task);

        // 5️⃣ Loga o movimento
        Log::info('Task moved between boards', [
            'task_id' => $dto->task_id,
            'from_board_id' => $dto->from_board_id,
            'to_board_id' => $dto->to_board_id,
            'moved_by' => $dto->moved_by,
        ]);

        return $task;
    }
}
