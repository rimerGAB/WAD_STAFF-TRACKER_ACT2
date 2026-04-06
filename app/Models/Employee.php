<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $primaryKey = 'emp_id';
    protected $fillable = ['name', 'dept_id'];

    // One-to-Many (inverse)
    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    // One-to-One
    public function profile()
    {
        return $this->hasOne(Profile::class, 'emp_id');
    }

    // Many-to-Many via pivot table
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'assignments', 'emp_id', 'proj_id')
                    ->withPivot('hours', 'role');
    }
}