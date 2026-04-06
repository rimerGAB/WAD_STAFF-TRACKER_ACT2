<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $primaryKey = 'dept_id';
    protected $fillable = ['name'];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'dept_id');
    }
}