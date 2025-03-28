<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'user',
            'id' => (string) $this->id,
            'attributes' => [
                'name' => $this->name,
                'email' => $this->email,
                'isManager' => $this->is_manager,
                $this->mergeWhen($request->routeIs('users.*'), [
                    'emailVerifiedAT' => $this->email_verified_at,
                    'createdAt' => $this->created_at,
                    'updatedAt' => $this->updated_at
                ])
            ],
            'included' => TaskResource::collection($this->whenLoaded('tasks')),   
            'links' => [
                'self' => route('users.show', ['user' => $this->id])
            ]
        ];
    }
}
