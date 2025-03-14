<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BaseUserRequest extends FormRequest
{
    
    public function mappedAttributes() 
    {
        $attributesMap = [
            'data.attributes.name' => 'name',
            'data.attributes.email' => 'email',
            'data.attributes.password' => 'password',
            'data.attributes.isManager' => 'is_manager'
        ];

        return collect($attributesMap)
            ->filter(fn($attribute, $key) => $this->has($key))
            ->mapWithKeys(fn($attribute, $key) => [$attribute => $this->input($key)])
            ->all();
    }       
}
