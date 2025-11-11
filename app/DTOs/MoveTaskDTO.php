<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class MoveTaskDTO
{
    public function __construct(
        public int $task_id,
        public int $from_board_id,
        public int $to_board_id,
        public ?int $moved_by = null,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            task_id: (int) $request->input('task_id'),
            from_board_id: (int) $request->input('from_board_id'),
            to_board_id: (int) $request->input('to_board_id'),
            moved_by: auth()->id() ?? null,
        );
    }
}
