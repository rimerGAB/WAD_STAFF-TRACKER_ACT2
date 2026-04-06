<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $primaryKey = 'prof_id';
    protected $fillable = ['emp_id', 'name', 'email'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }
}