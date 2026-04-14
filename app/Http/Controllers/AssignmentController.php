<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Employee;
use App\Models\Project;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    /**
     * Display a listing of assignments.
     */
    public function index()
    {
        if (auth()->user()->is_admin) {
            $assignments = Assignment::with(['employee', 'project'])->get();
        } else {
            $assignments = Assignment::with(['employee', 'project'])
                            ->where('emp_id', auth()->id())
                            ->get();
        }
        
        return view('assignments.index', compact('assignments'));
    }
    
    /**
     * Show form to create new assignment (admin only).
     */
    public function create()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action. Only admin can create assignments.');
        }
        
        $employees = Employee::with('department')->get();
        $projects = Project::all();
        
        return view('assignments.create', compact('employees', 'projects'));
    }
    
    /**
     * Store new assignment (admin only).
     */
    public function store(Request $request)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action. Only admin can create assignments.');
        }
        
        $request->validate([
            'emp_id' => 'required|exists:employees,emp_id',
            'proj_id' => 'required|exists:projects,proj_id',
            'hours' => 'required|integer|min:1|max:168',
            'role' => 'required|string|max:255'
        ]);
        
        // Check if assignment already exists
        $exists = Assignment::where('emp_id', $request->emp_id)
                            ->where('proj_id', $request->proj_id)
                            ->exists();
        
        if ($exists) {
            return redirect()->back()
                ->with('error', 'This employee is already assigned to this project.')
                ->withInput();
        }
        
        Assignment::create($request->all());
        
        return redirect()->route('assignments.index')
            ->with('success', 'Assignment created successfully.');
    }
    
    /**
     * Display specific assignment.
     */
    public function show(Assignment $assignment)
    {
        if (!auth()->user()->is_admin && $assignment->emp_id != auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $assignment->load(['employee', 'project']);
        return view('assignments.show', compact('assignment'));
    }
    
    /**
     * Show form to edit assignment (admin only).
     */
    public function edit(Assignment $assignment)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action. Only admin can edit assignments.');
        }
        
        $employees = Employee::with('department')->get();
        $projects = Project::all();
        
        return view('assignments.edit', compact('assignment', 'employees', 'projects'));
    }
    
    /**
     * Update assignment (admin only).
     */
    public function update(Request $request, Assignment $assignment)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action. Only admin can update assignments.');
        }
        
        $request->validate([
            'emp_id' => 'required|exists:employees,emp_id',
            'proj_id' => 'required|exists:projects,proj_id',
            'hours' => 'required|integer|min:1|max:168',
            'role' => 'required|string|max:255'
        ]);
        
        $assignment->update($request->all());
        
        return redirect()->route('assignments.index')
            ->with('success', 'Assignment updated successfully.');
    }
    
    /**
     * Delete assignment (admin only).
     */
    public function destroy(Assignment $assignment)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action. Only admin can delete assignments.');
        }
        
        $assignment->delete();
        
        return redirect()->route('assignments.index')
            ->with('success', 'Assignment deleted successfully.');
    }
}