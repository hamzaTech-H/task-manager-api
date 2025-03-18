<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
            'data.attributes.status' => ['sometimes','string',new Enum(TaskStatus::class)],
            'data.attributes.dueDate' => ['nullable', 'date'],
        ];
    }
}
