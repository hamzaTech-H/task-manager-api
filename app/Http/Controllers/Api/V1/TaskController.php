<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TaskFilter;
use App\Http\Requests\Api\V1\ReplaceTaskRequest;
use App\Http\Requests\Api\V1\StoreTaskRequest;
use App\Http\Requests\Api\V1\UpdateTaskRequest;
use App\Http\Resources\V1\TaskResource;
use App\Models\Task;


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
            $this->authorize('store', Task::class);

            return new TaskResource(Task::create($request->mappedAttributes()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        if ($this->include('user')) {
            return  new TaskResource($task->load('user'));
        }
        
        return new TaskResource($task);  
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
            $this->authorize('update', $task);
            
            $task->update($request->mappedAttributes());
    
            return new TaskResource($task);
    }

    public function replace(ReplaceTaskRequest $request, Task $task) 
    {
            $this->authorize('replace', $task);

            $task->update($request->mappedAttributes());

            return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {     
            $this->authorize('delete', $task);

            $task->delete();

            return $this->success('task successfully deleted');
    }
}