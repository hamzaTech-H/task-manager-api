<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BaseTaskRequest extends FormRequest
{
   
    public function mappedAttributes() 
    {
        $attributesMap = [
            'data.attributes.title' => 'title',
            'data.attributes.description' => 'description',
            'data.attributes.status' => 'status',
            'data.relationships.author.data.id' => 'user_id',
            'data.attributes.dueDate' => 'due_date'
        ];

        return collect($attributesMap)
            ->filter(fn($attribute, $key) => $this->has($key))
            ->mapWithKeys(fn($attribute, $key) => [$attribute => $attribute === 'password' ? bcrypt($this->input($key)) : $this->input($key)])
            ->all();
    }

    public function messages() 
    {
        return [
            'data.attributes.status' => 'The data.attributes.status value is invalid. Please use pending, in_progress, completed, on_hold, cancelled'
        ];
    }
}
