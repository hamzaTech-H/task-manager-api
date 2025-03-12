<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TaskFilter;
use App\Http\Requests\Api\V1\ReplaceTaskRequest;
use App\Http\Requests\Api\V1\StoreTaskRequest;
use App\Http\Requests\Api\V1\UpdateTaskRequest;
use App\Http\Resources\V1\TaskResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UserTasksController extends ApiController
{
    public function index(User $user, TaskFilter $filters) 
    {
        return TaskResource::collection($user->tasks()->filter($filters)->paginate());
    }

    public function store(StoreTaskRequest $request, $user_id)
    {
        try {
            $this->authorize('store', Task::class);
            
            return new TaskResource(Task::create(array_merge($request->mappedAttributes(), ['user_id' => $user_id])));
        }catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to create this task.', 403);
        } 
    }

    public function replace(ReplaceTaskRequest $request, $user_id,  $task_id) 
    {
        try {
            $task = Task::where('id', $task_id)
                            ->where('user_id', $user_id)
                            ->firstOrFail();

            $this->authorize('replace', $task);   
            $task->update($request->mappedAttributes());
            return new TaskResource($task);
            
            // TODO: task doesn't belong to user
    
        } catch (ModelNotFoundException $exception) {
            return $this->error('Task cannot be found.', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to replace this task.', 403);
        }
    }

    public function update(UpdateTaskRequest $request, $user_id,  $task_id) 
    {
        try {
            $task = Task::where('id', $task_id)
            ->where('user_id', $user_id)
            ->firstOrFail();

            $this->authorize('update', $task);
            
            $task->update($request->mappedAttributes());
            return new TaskResource($task);
       
            // TODO: task doesn't belong to user
    
        } catch (ModelNotFoundException $exception) {
            return $this->error('task cannot be found.', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to update this task.', 403);
        }
    }

    public function destroy($user_id, $task_id)
    {
        try {
            $task = Task::where('id', $task_id)
            ->where('user_id', $user_id)
            ->firstOrFail();

            $this->authorize('delete', $task);
            
            $task->delete();
            return $this->success('Task successfully deleted');
            
        } catch (ModelNotFoundException $exception) {
            return $this->error('Task cannot be found', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to replace this task.', 403);
        }
    }

}
