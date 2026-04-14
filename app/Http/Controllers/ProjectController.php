<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects.
     */
    public function index()
    {
        // Everyone can view projects, with eager loading
        $projects = Project::withCount('employees')->get();
        return view('projects.index', compact('projects'));
    }
    
    /**
     * Show form to create new project (admin only).
     */
    public function create()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('projects.create');
    }
    
    /**
     * Store new project (admin only).
     */
    public function store(Request $request)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'budget' => 'required|numeric|min:0'
        ]);
        
        Project::create($request->all());
        
        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }
    
    /**
     * Display specific project.
     */
    public function show(Project $project)
    {
        // Eager loading to avoid N+1 query
        $project->load('employees', 'assignments.employee');
        return view('projects.show', compact('project'));
    }
    
    /**
     * Show form to edit project (admin only).
     */
    public function edit(Project $project)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('projects.edit', compact('project'));
    }
    
    /**
     * Update project (admin only).
     */
    public function update(Request $request, Project $project)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'budget' => 'required|numeric|min:0'
        ]);
        
        $project->update($request->all());
        
        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully.');
    }
    
    /**
     * Delete project (admin only).
     */
    public function destroy(Project $project)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if project has assignments
        $assignmentCount = $project->assignments()->count();
        if ($assignmentCount > 0) {
            return redirect()->route('projects.index')
                ->with('error', "Cannot delete project with {$assignmentCount} active assignment(s). Please delete all assignments first.");
        }
        
        $project->delete();
        
        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}