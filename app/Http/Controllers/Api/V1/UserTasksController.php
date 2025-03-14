<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TaskFilter;
use App\Http\Requests\Api\V1\ReplaceTaskRequest;
use App\Http\Requests\Api\V1\StoreTaskRequest;
use App\Http\Requests\Api\V1\UpdateTaskRequest;
use App\Http\Resources\V1\TaskResource;
use App\Models\Task;
use App\Models\User;

class UserTasksController extends ApiController
{
    public function index(User $user, TaskFilter $filters) 
    {
        return TaskResource::collection($user->tasks()->filter($filters)->paginate());
    }
 
    public function store(StoreTaskRequest $request, $user_id)
    {
            $this->authorize('store', Task::class);
            
            return new TaskResource(Task::create(array_merge($request->mappedAttributes(), ['user_id' => $user_id])));
    }

    public function replace(ReplaceTaskRequest $request, $user_id,  $task_id) 
    {
            $task = Task::where('id', $task_id)
                            ->where('user_id', $user_id)
                            ->firstOrFail();

            $this->authorize('replace', $task);   
            $task->update($request->mappedAttributes());

            return new TaskResource($task);          
    }

    public function update(UpdateTaskRequest $request, $user_id,  $task_id) 
    {
            $task = Task::where('id', $task_id)
            ->where('user_id', $user_id)
            ->firstOrFail();

            $this->authorize('update', $task);
            
            $task->update($request->mappedAttributes());

            return new TaskResource($task);
    }

    public function destroy($user_id, $task_id)
    {
            $task = Task::where('id', $task_id)
            ->where('user_id', $user_id)
            ->firstOrFail();

            $this->authorize('delete', $task);
            
            $task->delete();

            return $this->success('Task successfully deleted');
    }
}