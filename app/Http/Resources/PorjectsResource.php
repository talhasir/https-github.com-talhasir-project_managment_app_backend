<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PorjectsResource extends JsonResource
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
            'name' => $this->name,
            'image_path' => $this->image_path,
            'status' => $this->status,
            'created_at' => (new \Carbon\Carbon($this->created_at))->format('d-m-y'),
            'due_date' => (new \Carbon\Carbon($this->due_date))->format('d-m-y'),
            'description' => $this->description,
            'created_by' => $this->created_by,
        ];
    }
}
