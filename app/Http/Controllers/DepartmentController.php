<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments.
     */
    public function index()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        $departments = Department::withCount('employees')->get();
        return view('departments.index', compact('departments'));
    }
    
    /**
     * Show form to create new department.
     */
    public function create()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('departments.create');
    }
    
    /**
     * Store new department.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name'
        ]);
        
        Department::create($request->all());
        
        return redirect()->route('departments.index')
            ->with('success', 'Department created successfully.');
    }
    
    /**
     * Display specific department.
     */
    public function show(Department $department)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        $department->load('employees');
        return view('departments.show', compact('department'));
    }
    
    /**
     * Show form to edit department.
     */
    public function edit(Department $department)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('departments.edit', compact('department'));
    }
    
    /**
     * Update department.
     */
    public function update(Request $request, Department $department)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->dept_id . ',dept_id'
        ]);
        
        $department->update($request->all());
        
        return redirect()->route('departments.index')
            ->with('success', 'Department updated successfully.');
    }
    
    /**
     * Delete department.
     */
    public function destroy(Department $department)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if department has employees
        $employeeCount = $department->employees()->count();
        if ($employeeCount > 0) {
            return redirect()->route('departments.index')
                ->with('error', "Cannot delete department with {$employeeCount} employee(s). Please move or delete employees first.");
        }
        
        $department->delete();
        
        return redirect()->route('departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}