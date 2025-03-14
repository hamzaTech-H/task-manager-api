<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;
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
        $user = $this->user();
        $authorIdAttr = $this->routeIs('tasks.store') ? 'data.relationships.author.data.id' : 'user';

        $rules = [
            'data.attributes.title' => ['required','string'],
            'data.attributes.description' => ['required','string'],
            'data.attributes.status' => ['required','string','in:pending,in_progress,completed,on_hold,cancelled'],
            'data.attributes.dueDate' => ['nullable', 'date'],
            $authorIdAttr => ['required','integer','exists:users,id',"size:{$user->id}"]
        ];

        if ($user->tokenCan(Abilities::CreateTask)) {
            $rules[$authorIdAttr] = ['required','integer','exists:users,id'];
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        if ($this->routeIs('users.tasks.store')) {
            $this->merge([
                'user' => $this->route('user')
            ]);
        }
    }
}
