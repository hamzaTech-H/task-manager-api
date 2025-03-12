<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
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
use PhpParser\Builder\Class_;

class TaskController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(TaskFilter $filters)
    {
        return TaskResource::collection(Task::filter($filters)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        try {
            
            $this->authorize('store', Task::class);

            return new TaskResource(Task::create($request->mappedAttributes()));
        
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to create this task.', 403);
        } 
    }

    /**
     * Display the specified resource.
     */
    public function show($task_id)
    {
        try {
            $task = Task::query()->findOrFail($task_id);

            if ($this->include('user')) {
                return  new TaskResource($task->load('user'));
            }
            
            return new TaskResource($task);
        }   catch (ModelNotFoundException $exception) {
            return $this->error('Task cannot be found.', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, $task_id)
    {
        try {
            $task = Task::findOrFail($task_id);

            $this->authorize('update', $task);
            
            $task->update($request->mappedAttributes());
    
            return new TaskResource($task);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Task cannot be found.', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to update this task.', 403);
        }

    }

    public function replace(ReplaceTaskRequest $request, $task_id) 
    {
        try {
            $task = Task::findOrFail($task_id);

            $this->authorize('replace', $task);

            $task->update($request->mappedAttributes());

            return new TaskResource($task);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Task cannot be found.', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to replace this task.', 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($task_id)
    {
        try {
            $task = Task::findOrFail($task_id);

            $this->authorize('delete', $task);
            $task->delete();

            return $this->success('task successfully deleted');
        } catch (ModelNotFoundException $exception) {
            return $this->error('task can not be found.', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to delete this task.', 403);
        }

    }
}
