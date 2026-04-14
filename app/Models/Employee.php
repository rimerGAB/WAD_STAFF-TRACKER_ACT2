<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $primaryKey = 'emp_id';
    protected $table = 'employees';
    
    protected $fillable = [
        'name', 'email', 'password', 'dept_id', 'is_admin'
    ];
    
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    protected $casts = [
        'is_admin' => 'boolean',
    ];
    
    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id', 'dept_id');
    }
    
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'emp_id', 'emp_id');
    }
    
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'assignments', 'emp_id', 'proj_id')
                    ->withPivot('hours', 'role')
                    ->withTimestamps();
    }
}