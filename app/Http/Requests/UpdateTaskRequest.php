<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'board_id' => 'sometimes|integer|exists:boards,id',
            'assigned_to' => 'sometimes|nullable|integer|exists:users,id',
            'priority' => 'sometimes|string|in:low,medium,high',
            'due_date' => 'sometimes|date',
        ];
    }
}
