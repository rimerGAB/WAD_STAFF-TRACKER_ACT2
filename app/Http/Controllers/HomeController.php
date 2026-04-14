<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Project;
use App\Models\Assignment;

class HomeController extends Controller
{
    public function landing()
    {
        // Get stats for landing page
        $totalEmployees = Employee::count();
        $totalProjects = Project::count();
        $totalHours = Assignment::sum('hours');
        
        return view('landing', compact('totalEmployees', 'totalProjects', 'totalHours'));
    }
    
    public function index()
    {
        return view('home');
    }
}