<?php

namespace App\Http\Controllers;

use App\Models\Projects;
use App\Http\Requests\StoreProjectsRequest;
use App\Http\Requests\UpdateProjectsRequest;
use Illuminate\Http\Request;
use App\Http\Resources\PorjectsResource;
use App\Http\Resources\ProjectPaginationResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
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

        if (isset($queryParams['filters']['name'])) {
            $searchTerm = $queryParams['filters']['name'][0];
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }
        if (isset($queryParams['filters']['created_by'])) {
            $searchTerm = $queryParams['filters']['name'][0];
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }
        if (isset($queryParams['filters']['status'])) {
            $searchTerm = $queryParams['filters']['status'][0];
            $query->where('status', 'like', '%' . $searchTerm . '%');
        }

        $projects = $query->orderBy('id', 'desc')->paginate(10);

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
        $project = $request->validated();
        $projectStored = Projects::create($project);

        // dd($project);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_path = $image->store('images'); // Save the image to the 'images' directory
            $projectStored->image_path = $image_path; // Update the image path in the database
            $projectStored->save();
        }

        if ($projectStored) {
            return response(['Success' => 'Project created successfully']);
        } else {
            return response(['Error' => 'Project not created']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Projects $project)
    {
        return ['project' => new PorjectsResource($project)];
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
    public function update(Request $request, Projects $projects)
    {

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'status' => 'required|string|max:100',
            'due_date' => 'nullable|date',
            'created_by' => 'required',
            // 'updated_by' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $formattedDueDate = $data['due_date'] 
        ? Carbon::parse($data['due_date'])->format('Y-m-d') 
        : null;

        $project = Projects::find($request->id);
        $project['name'] = $request->input('name');
        $project['status'] = $request->input('status');
        $project['description'] = $request->input('description');
        $project['due_date'] = $formattedDueDate;
        $project->save();
        return ['updated'];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $toDelete = Projects::find($id);
        if ($toDelete->delete()) {
            return['success' => 'deleted project successfully'];
        }else{
            return['error'];
        }
        // $name = $projects->name;
        
        // if($projects->delete()){
        //     return ['success' => 'Project ' . $name . ' deleted successfully'];
        // } else {
        //     return ['error' => 'Failed to delete project'];
        // }
    }
    
}
