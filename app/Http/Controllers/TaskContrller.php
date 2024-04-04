<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use App\Http\Requests\StoreTasksRequest;
use App\Http\Requests\UpdateTasksRequest;
use App\Http\Resources\TasksResource;
use Illuminate\Console\View\Components\Task;
use Illuminate\Http\Request;

class TaskContrller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tasks::query();
        $queryParams =  $request->query();

        if (isset($queryParams['filters']['project_name'])) {
            $searchTerm = $queryParams['filters']['project_name'][0];
            $query->where('projects_id', 'like', '%'.$searchTerm.'%');
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
        
        $projects = $query->paginate(10)->onEachSide(1);
         return response()->json([
            'tasks' => [
                'data' => TasksResource::collection($projects),
                'queryParams' => $queryParams,
                'pagination' => [
                    'total' => $projects->total(),
                    'per_page' => $projects->perPage(),
                    'current_page' => $projects->currentPage(),
                    'last_page' => $projects->lastPage(),
                    'from' => $projects->firstItem(),
                    'to' => $projects->lastItem(),
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Tasks $tasks)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tasks $tasks)
    {
        //
    }
}
