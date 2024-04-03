<?php

namespace App\Http\Controllers;

use App\Models\Projects;
use App\Http\Requests\StoreProjectsRequest;
use App\Http\Requests\UpdateProjectsRequest;
use Illuminate\Http\Request;
use App\Http\Resources\PorjectsResource;
use App\Http\Resources\ProjectPaginationResource;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class ProjectContrller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Projects::query();
        $queryParams =  $request->query();

        $sortField = $request->query('sort_field');
        $sortDirection = $request->query('sort_direction');

        // return [$sortDirection, $sortField];

        if (isset($queryParams['search'])) {
            $searchTerm = $queryParams['search'];
            $query->where('name', 'like', '%'.$searchTerm.'%');
        }
        if (isset($queryParams['select'])) {
            $searchTerm = $queryParams['select'];
            $query->where('status', 'like', '%'.$searchTerm.'%');
        }
        
        $projects = $query->paginate(10)->onEachSide(1);
         return response()->json([
            'projects' => [
                'data' => PorjectsResource::collection($projects),
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
    public function store(StoreProjectsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Projects $projects)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Projects $projects)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectsRequest $request, Projects $projects)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Projects $projects)
    {
        //
    }
}
