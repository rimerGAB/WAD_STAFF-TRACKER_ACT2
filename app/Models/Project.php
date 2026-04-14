<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'proj_id';
    protected $fillable = ['title', 'budget'];
    
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'assignments', 'proj_id', 'emp_id')
                    ->withPivot('hours', 'role');
    }
    
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'proj_id', 'proj_id');
    }
}