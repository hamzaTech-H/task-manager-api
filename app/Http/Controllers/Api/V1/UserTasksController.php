<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TaskFilter;
use App\Http\Requests\Api\V1\ReplaceTaskRequest;
use App\Http\Requests\Api\V1\StoreTaskRequest;
use App\Http\Requests\Api\V1\UpdateTaskRequest;
use App\Http\Resources\V1\TaskResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UserTasksController extends ApiController
{
    public function index(User $user, TaskFilter $filters) 
    {
        return TaskResource::collection($user->tasks()->filter($filters)->paginate());
    }

    public function store($user_id, StoreTaskRequest $request)
    {
        return new TaskResource(Task::create(array_merge($request->mappedAttributes(), ['user_id' => $user_id])));
    }

    public function replace(ReplaceTaskRequest $request, $user_id,  $task_id) 
    {
        try {
            $task = Task::findOrFail($task_id);

            if ($task->user_id == $user_id) {
                
                $task->update($request->mappedAttributes());
                return new TaskResource($task);
            }
            // TODO: task doesn't belong to user
    
        } catch (ModelNotFoundException $exception) {
            return $this->error('task cannot be found.', 404);
        }
    }

    public function update(UpdateTaskRequest $request, $user_id,  $task_id) 
    {
        try {
            $task = Task::findOrFail($task_id);

            if ($task->user_id == $user_id) {
                $task->update($request->mappedAttributes());
                return new TaskResource($task);
            }
            // TODO: task doesn't belong to user
    
        } catch (ModelNotFoundException $exception) {
            return $this->error('task cannot be found.', 404);
        }
    }

    public function destroy($user_id, $task_id)
    {
        try {
            $task = Task::findOrFail($task_id);

            if ($task->user_id == $user_id) {
                $task->delete();
                return $this->success('Task successfully deleted');
            }

            return $this->error('Task cannot be found', 404);
            
        } catch (ModelNotFoundException $exception) {
            return $this->error('Task cannot be found', 404);
        }
    }

}
