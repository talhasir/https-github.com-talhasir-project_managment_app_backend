<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use App\Http\Requests\StoreTasksRequest;
use App\Http\Requests\UpdateTasksRequest;
use App\Http\Resources\TasksResource;
use App\Models\Tasks as ModelsTasks;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Console\View\Components\Task;

class TaskContrller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tasks::query();
        $queryParams =  $request->query();

        if (isset($queryParams['filters']['task_name'])) {
            $searchTerm = $queryParams['filters']['task_name'][0];
            $query->where('tasks_id', 'like', '%'.$searchTerm.'%');
        }
        if (isset($queryParams['filters']['name'])) {
            $searchTerm = $queryParams['filters']['name'][0];
            $query->where('name', 'like', '%'.$searchTerm.'%');
        }
        if (isset($queryParams['filters']['created_by'])) {
            $searchTerm = $queryParams['filters']['name'][0];
            $query->where('name', 'like', '%'.$searchTerm.'%');
        }
        if (isset($queryParams['filters']['status'])) {
            $searchTerm = $queryParams['filters']['status'][0];
            $query->where('status', 'like', '%'.$searchTerm.'%');
        }
        if (isset($queryParams['filters']['priority'])) {
            $searchTerm = $queryParams['filters']['priority'][0];
            $query->where('priority', 'like', '%'.$searchTerm.'%');
        }
        
        $tasks = $query->orderBy('id', 'desc')->paginate(10);

         return response()->json([
            'tasks' => [
                'data' => TasksResource::collection($tasks),
                'queryParams' => $queryParams,
                'pagination' => [
                    'total' => $tasks->total(),
                    'per_page' => $tasks->perPage(),
                    'current_page' => $tasks->currentPage(),
                    'last_page' => $tasks->lastPage(),
                    'from' => $tasks->firstItem(),
                    'to' => $tasks->lastItem(),
                ],
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTasksRequest $request)
    {
        $task = $request->validated();
        $taskStored = Tasks::create($task);

        // dd($task);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_path = $image->store('images'); // Save the image to the 'images' directory
            $taskStored->image_path = $image_path; // Update the image path in the database
            $taskStored->save();
        }

        if ($taskStored) {
            return response(['Success' => 'Task created successfully']);
        } else {
            return response(['Error' => 'Task not created']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $toShowtask = ModelsTasks::find($id);
        return ['task' => new TasksResource($toShowtask)];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tasks $tasks)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTasksRequest $request, Tasks $tasks)
    {
        $data = $request->validated();
    
        $data['due_date'] = Carbon::parse($data['due_date'])->format('Y-m-d');
    
        $task = Tasks::findOrFail($request->id);
    
        $task->name = $data['name'];
        $task->projects_id = $data['projects_id'];
        $task->assigned_user_id = $data['assigned_user_id'];
        $task->status = $data['status'];
        $task->priority = $data['priority'];
        $task->description = $data['description'];
        $task->due_date = $data['due_date'];
        $task->created_by = $data['created_by'];
        $task->updated_by = $data['updated_by'];
    
        if ($request->hasFile('image')) {
            // Process image upload and update image_path attribute
            // Example: $task->image_path = $request->file('image')->store('images');
        }

        if ($task->save()) {
            return ['updated'];
        } else {
            return ['not updated'];
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $toDelete = Tasks::find($id);
        if ($toDelete->delete()) {
            return['success' => 'deleted task successfully'];
        }else{
            return['error'];
        }
        // $name = $tasks->name;
        
        // if($tasks->delete()){
        //     return ['success' => 'Task ' . $name . ' deleted successfully'];
        // } else {
        //     return ['error' => 'Failed to delete task'];
        // }
    }

    public function myTasks(Request $request)
    {
        $myId = auth()->user();
        // return $myId;
        $query = Tasks::query()->where('assigned_user_id', $myId->id);
        $queryParams =  $request->query();

        if (isset($queryParams['filters']['task_name'])) {
            $searchTerm = $queryParams['filters']['task_name'][0];
            $query->where('tasks_id', 'like', '%'.$searchTerm.'%');
        }
        if (isset($queryParams['filters']['name'])) {
            $searchTerm = $queryParams['filters']['name'][0];
            $query->where('name', 'like', '%'.$searchTerm.'%');
        }
        if (isset($queryParams['filters']['created_by'])) {
            $searchTerm = $queryParams['filters']['name'][0];
            $query->where('name', 'like', '%'.$searchTerm.'%');
        }
        if (isset($queryParams['filters']['status'])) {
            $searchTerm = $queryParams['filters']['status'][0];
            $query->where('status', 'like', '%'.$searchTerm.'%');
        }
        if (isset($queryParams['filters']['priority'])) {
            $searchTerm = $queryParams['filters']['priority'][0];
            $query->where('priority', 'like', '%'.$searchTerm.'%');
        }

        $tasks = $query->orderBy('id', 'desc')->paginate(10);

         return response()->json([
            'tasks' => [
                'data' => TasksResource::collection($tasks),
                'queryParams' => $queryParams,
                'pagination' => [
                    'total' => $tasks->total(),
                    'per_page' => $tasks->perPage(),
                    'current_page' => $tasks->currentPage(),
                    'last_page' => $tasks->lastPage(),
                    'from' => $tasks->firstItem(),
                    'to' => $tasks->lastItem(),
                ],
            ]
        ]);
    }
    
    public function projectAssciatedTasks(Request $request)
    {
        $projectId = $request->id;
        // return $projectId;
        $query = Tasks::query()->where('projects_id', $projectId);
        $queryParams =  $request->query();

        if (isset($queryParams['filters']['task_name'])) {
            $searchTerm = $queryParams['filters']['task_name'][0];
            $query->where('tasks_id', 'like', '%'.$searchTerm.'%');
        }
        if (isset($queryParams['filters']['name'])) {
            $searchTerm = $queryParams['filters']['name'][0];
            $query->where('name', 'like', '%'.$searchTerm.'%');
        }
        if (isset($queryParams['filters']['created_by'])) {
            $searchTerm = $queryParams['filters']['name'][0];
            $query->where('name', 'like', '%'.$searchTerm.'%');
        }
        if (isset($queryParams['filters']['status'])) {
            $searchTerm = $queryParams['filters']['status'][0];
            $query->where('status', 'like', '%'.$searchTerm.'%');
        }
        if (isset($queryParams['filters']['priority'])) {
            $searchTerm = $queryParams['filters']['priority'][0];
            $query->where('priority', 'like', '%'.$searchTerm.'%');
        }

        $tasks = $query->orderBy('id', 'desc')->paginate(10);

         return response()->json([
            'tasks' => [
                'data' => TasksResource::collection($tasks),
                'queryParams' => $queryParams,
                'pagination' => [
                    'total' => $tasks->total(),
                    'per_page' => $tasks->perPage(),
                    'current_page' => $tasks->currentPage(),
                    'last_page' => $tasks->lastPage(),
                    'from' => $tasks->firstItem(),
                    'to' => $tasks->lastItem(),
                ],
            ]
        ]);
    }
}
