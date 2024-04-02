<?php

namespace App\Http\Controllers;

use App\Models\Projects;
use App\Http\Requests\StoreProjectsRequest;
use App\Http\Requests\UpdateProjectsRequest;
use App\Http\Resources\PorjectsResource;
use App\Http\Resources\ProjectPaginationResource;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class ProjectContrller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Projects::query();
        $projects = $query->paginate(10)->onEachSide(1);

        // return $paginationLinks;
        return response()->json([
            'projects' => $projects
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
