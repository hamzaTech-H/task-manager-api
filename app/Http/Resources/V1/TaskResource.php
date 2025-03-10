<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'task',
            'id' => (string) $this->id,
            'attributes' => [
                'title' => $this->title,
                'description' => $this->when(
                    $request->routeIs('tasks.show'),
                    $this->description
                ),
                'status' => $this->status,
                'dueDate' => $this->due_date,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at
            ],
            'relationships' => [
                'author' => [
                    'data' => [
                        'type' => 'user',
                        'id' => (string) $this->user_id
                    ],
                    'links' => [
                        'self' => route('users.show', ['user' => $this->user_id])
                    ]
                ]
            ],
            'included' => new UserResource($this->whenLoaded('user')),
            'links' => [
                'self' => route('tasks.show', $this->id),
            ],
        ];
    }
}
