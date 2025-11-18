<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class NotificationDTO
{
    public function __construct(
        public int $user_id,
        public int $task_id,
        public string $message
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            user_id: (int) $request->input('user_id'),
            task_id: (int) $request->input('task_id'),
            message: $request->input('message')
        );
    }
}
