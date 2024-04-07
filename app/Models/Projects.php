<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'description', 'status', 'due_date', 'image_path', 'created_by', 'updated_by'];

    public function tasks ()
    {
        return $this->hasMany(Tasks::class);
    }

    public function created_by(){
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function updated_by(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    // public function setDueDateAttribute($value)
    // {
    //     $this->attributes['due_date'] = date('d-m-Y', strtotime($value));
    // }
}   
