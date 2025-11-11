<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class TaskDTO
{
    public function __construct(
        public string $title,
        public ?string $description,
        public int $board_id,
        public ?int $assigned_to,
        public ?string $priority,
        public ?string $due_date
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            title: $request->input('title'),
            description: $request->input('description'),
            board_id: (int) $request->input('board_id'),
            assigned_to: $request->input('assigned_to') ? (int) $request->input('assigned_to') : null,
            priority: $request->input('priority'),
            due_date: $request->input('due_date')
        );
    }
}
