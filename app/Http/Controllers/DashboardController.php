<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Project;
use App\Models\Assignment;
use App\Models\Department;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic stats
        $totalEmployees = Employee::count();
        $totalProjects = Project::count();
        $totalDepartments = Department::count();
        $totalAssignments = Assignment::count();
        $totalHours = Assignment::sum('hours');
        $totalBudget = Project::sum('budget');
        
        // Department Chart Data - Make sure we have data
        $departments = Department::withCount('employees')->get();
        $departmentNames = $departments->pluck('name')->toArray();
        $departmentCounts = $departments->pluck('employees_count')->toArray();
        
        // If no departments, add dummy data for testing
        if (empty($departmentNames)) {
            $departmentNames = ['No Departments Yet'];
            $departmentCounts = [0];
        }
        
        // Top Projects by Budget
        $topProjects = Project::orderBy('budget', 'desc')->take(5)->get();
        $projectNames = $topProjects->pluck('title')->toArray();
        $projectBudgets = $topProjects->pluck('budget')->toArray();
        
        // If no projects, add dummy data for testing
        if (empty($projectNames)) {
            $projectNames = ['No Projects Yet'];
            $projectBudgets = [0];
        }
        
        // Recent assignments
        $recentAssignments = Assignment::with(['employee', 'project'])
            ->latest()
            ->take(5)
            ->get();
        
        // Top employees by hours
        $topEmployees = Employee::withSum('assignments', 'hours')
            ->orderBy('assignments_sum_hours', 'desc')
            ->take(5)
            ->get();
        
        // Project hours distribution
        $hoursProjectNames = [];
        $hoursProjectTotals = [];
        
        $projects = Project::all();
        foreach ($projects as $project) {
            $total = Assignment::where('proj_id', $project->proj_id)->sum('hours');
            if ($total > 0) {
                $hoursProjectNames[] = $project->title;
                $hoursProjectTotals[] = $total;
            }
        }
        
        // Current user's assignments
        $myAssignments = null;
        if (!auth()->user()->is_admin) {
            $myAssignments = Assignment::with('project')
                ->where('emp_id', auth()->id())
                ->get();
        }
        
        return view('dashboard', [
            'totalEmployees' => $totalEmployees,
            'totalProjects' => $totalProjects,
            'totalDepartments' => $totalDepartments,
            'totalAssignments' => $totalAssignments,
            'totalHours' => $totalHours,
            'totalBudget' => $totalBudget,
            'departmentNames' => $departmentNames,
            'departmentCounts' => $departmentCounts,
            'projectNames' => $projectNames,
            'projectBudgets' => $projectBudgets,
            'recentAssignments' => $recentAssignments,
            'topEmployees' => $topEmployees,
            'hoursProjectNames' => $hoursProjectNames,
            'hoursProjectTotals' => $hoursProjectTotals,
            'myAssignments' => $myAssignments,
        ]);
    }
}