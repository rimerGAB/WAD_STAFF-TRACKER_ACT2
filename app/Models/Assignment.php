<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'assign_id';
    protected $fillable = ['emp_id', 'proj_id', 'hours', 'role'];
    
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id', 'emp_id');
    }
    
    public function project()
    {
        return $this->belongsTo(Project::class, 'proj_id', 'proj_id');
    }
}