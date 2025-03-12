<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BaseTaskRequest extends FormRequest
{
   
    public function mappedAttributes() 
    {
        $attributeMap = [
            'data.attributes.title' => 'title',
            'data.attributes.description' => 'description',
            'data.attributes.status' => 'status',
            'data.relationships.author.data.id' => 'user_id',
            'data.attributes.dueDate' => 'due_date'
        ];

        $attributesToUpdate = [];
        foreach ($attributeMap as $key => $attribute) {
            if ($this->has($key)) {
                $attributesToUpdate[$attribute] = $this->input($key);
            }
        }

        return $attributesToUpdate;
    }

    public function messages() 
    {
        return [
            'data.attributes.status' => 'The data.attributes.status value is invalid. Please use pending, in_progress, completed, on_hold, cancelled'
        ];
    }
}
