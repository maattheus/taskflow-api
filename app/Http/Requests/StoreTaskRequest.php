<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'board_id' => 'required|integer|exists:boards,id',
            'assigned_to' => 'nullable|integer|exists:users,id',
            'priority' => 'nullable|integer|in:1,2,3',
            'due_date' => 'nullable|date',
        ];
    }
}
