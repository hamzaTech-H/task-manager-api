<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends BaseTaskRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data.attributes.title' => ['sometimes','string'],
            'data.attributes.description' => ['sometimes','string'],
            'data.attributes.status' => ['sometimes','string','in:pending,in_progress,completed,on_hold,cancelled'],
            'data.attributes.dueDate' => ['nullable', 'date'],
        ];
    }
}
