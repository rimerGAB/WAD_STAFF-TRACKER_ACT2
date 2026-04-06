<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Assignment;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Department
        $dept = Department::create(['name' => 'IT Department']);
        
        // Create Employee
        $emp = Employee::create([
            'name' => 'John Doe',
            'dept_id' => $dept->dept_id
        ]);
        
        // Create Profile (1:1)
        Profile::create([
            'emp_id' => $emp->emp_id,
            'name' => 'John',
            'email' => 'john@example.com'
        ]);
        
        // Create Project
        $project = Project::create([
            'title' => 'Laravel React App',
            'budget' => 50000
        ]);
        
        // Create Assignment (N:M)
        Assignment::create([
            'emp_id' => $emp->emp_id,
            'proj_id' => $project->proj_id,
            'hours' => 40,
            'role' => 'Developer'
        ]);
    }
}