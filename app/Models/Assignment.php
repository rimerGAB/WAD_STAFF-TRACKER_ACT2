<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $table = 'assignments';
    protected $primaryKey = 'Assign_id';
    protected $fillable = ['emp_id', 'proj_id', 'hours', 'role'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'proj_id');
    }
}