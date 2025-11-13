<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class BoardDTO
{
    public function __construct(
        public string $name,
        public int $project_id
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            project_id: (int) $request->input('project_id')
        );
    }
}