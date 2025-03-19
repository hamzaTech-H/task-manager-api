<?php

namespace App\Http\Controllers\Api\V1;

use App\Exports\TasksExport;
use App\Http\Filters\V1\TaskFilter;
use App\Http\Requests\Api\V1\ReplaceTaskRequest;
use App\Http\Requests\Api\V1\StoreTaskRequest;
use App\Http\Requests\Api\V1\UpdateTaskRequest;
use App\Http\Resources\V1\TaskResource;
use App\Mail\TaskExportCompleted;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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

    public function exportTasks(Request $request)
    {
        $this->authorize('export', Task::class);

        $fileName = 'tasks.xlsx';
        $downloadUrl = asset(Storage::url($fileName));
       
        $user = $request->user();
        (new TasksExport())->store($fileName, 'public')->chain([
            function () use($downloadUrl, $user) {
                Mail::to($user)->queue(new TaskExportCompleted($downloadUrl));
            }
        ]);

        return response()->json([
            'message' => 'Task export is in progress. You will receive an email with the download link once it is ready.',
        ]);
    }

}