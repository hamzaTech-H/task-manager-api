<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends BaseTaskRequest
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
        $rules = [
            'data.attributes.title' => ['required','string'],
            'data.attributes.description' => ['required','string'],
            'data.attributes.status' => ['required','string','in:pending,in_progress,completed,on_hold,cancelled'],
            'data.attributes.dueDate' => ['nullable', 'date']
        ];

        if ($this->routeIs('tasks.store')) {
            $rules['data.relationships.author.data.id'] = ['required','integer'];
        }

        return $rules;
    }
}
