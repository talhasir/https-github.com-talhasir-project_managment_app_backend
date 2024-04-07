<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'projects_id',
        'assigned_user_id',
        'status',
        'priority',
        'description',
        'due_date',
        'created_by',
        'updated_by',
        'image',
    ];
}
