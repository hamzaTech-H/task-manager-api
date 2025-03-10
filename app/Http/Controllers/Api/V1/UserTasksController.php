<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TaskFilter;
use App\Http\Resources\V1\TaskResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserTasksController extends ApiController
{
    public function index(User $user, TaskFilter $filters) {
        return TaskResource::collection($user->tasks()->filter($filters)->paginate());
    }
}
