<?php

namespace App\Http\Controllers;

use App\Http\Resources\TasksResource;
use App\Models\Tasks;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $query = Tasks::query();
        $queryParams =  $request->query();

        if (isset($queryParams['filters']['task_name'])) {
            $searchTerm = $queryParams['filters']['task_name'][0];
            $query->where('tasks_id', 'like', '%' . $searchTerm . '%');
        }
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
        if (isset($queryParams['filters']['priority'])) {
            $searchTerm = $queryParams['filters']['priority'][0];
            $query->where('priority', 'like', '%' . $searchTerm . '%');
        }

        $inProcessTasks = Tasks::query()->where('status', 'in_process')->where('assigned_user_id', $request->user()->id)->count();
        $totalInProcessTasks = Tasks::query()->where('status', 'in_process')->count();
        $pendingTasks = Tasks::query()->where('status', 'pending')->where('assigned_user_id', $request->user()->id)->count();
        $totalPendingTasks = Tasks::query()->where('status', 'pending')->count();
        $completedTasks = Tasks::query()->where('status', 'completed')->where('assigned_user_id', $request->user()->id)->count();
        $totalCompletedTasks = Tasks::query()->where('status', 'completed')->count();

        $MyActiveTasks = $query
            ->whereIn('status', ['pending', 'in_process'])
            ->where('assigned_user_id', $request->user()->id)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return response()->json([
            'tasks' => [
                'data' => TasksResource::collection($MyActiveTasks),
                'task_counts' => [
                    'in_process_tasks' => Tasks::query()->where('status', 'in_process')
                    ->where('assigned_user_id', $request->user()->id)->count(),
                    'total_in_process_tasks' => Tasks::query()->where('status', 'in_process')->count(),
                    'pending_tasks' => Tasks::query()->where('status', 'pending')->where('assigned_user_id', $request->user()->id)->count(),
                    'total_pending_tasks' => Tasks::query()->where('status', 'pending')->count(),
                    'completed_tasks' => Tasks::query()->where('status', 'completed')->where('assigned_user_id', $request->user()->id)->count(),
                    'total_completed_tasks' => Tasks::query()->where('status', 'completed')->count(),
                ],
                'queryParams' => $queryParams,
                'pagination' => [
                    'total' => $MyActiveTasks->total(),
                    'per_page' => $MyActiveTasks->perPage(),
                    'current_page' => $MyActiveTasks->currentPage(),
                    'last_page' => $MyActiveTasks->lastPage(),
                    'from' => $MyActiveTasks->firstItem(),
                    'to' => $MyActiveTasks->lastItem(),
                ],
            ]

        ]);
    }
}
