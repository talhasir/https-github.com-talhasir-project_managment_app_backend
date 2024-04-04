<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TasksResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'project_name' => $this->projects_id,
            'name' => $this->name,
            'image_path' => $this->image_path,
            'status' => $this->status,
            'priority' => $this->priority,
            'to_assigned' => $this->assigned_user_id,
            'created_at' => Carbon::parse($this->created_at)->format('d-m-y'),
            'due_date' =>  Carbon::parse($this->due_date)->format('d-m-y'),
            'description' => $this->description,
            'created_by' => $this->created_by,
        ];
    }
}
