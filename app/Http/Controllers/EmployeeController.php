<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees.
     */
    public function index()
    {
        if (auth()->user()->is_admin) {
            // Admin sees all employees with their departments
            $employees = Employee::with('department')->get();
        } else {
            // Regular employees only see themselves
            $employees = Employee::with('department')
                        ->where('emp_id', auth()->id())
                        ->get();
        }
        
        return view('employees.index', compact('employees'));
    }
    
    /**
     * Show form to create new employee (admin only).
     */
    public function create()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        $departments = Department::all();
        return view('employees.create', compact('departments'));
    }
    
    /**
     * Store new employee (admin only).
     */
    public function store(Request $request)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'password' => 'required|min:8',
            'dept_id' => 'required|exists:departments,dept_id',
        ]);
        
        Employee::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'dept_id' => $request->dept_id,
            'is_admin' => $request->has('is_admin') ? true : false,
        ]);
        
        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }
    
    /**
     * Display specific employee.
     */
    public function show(Employee $employee)
    {
        // Check if user is authorized to view this employee
        if (!auth()->user()->is_admin && auth()->id() != $employee->emp_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $employee->load('department', 'assignments.project');
        return view('employees.show', compact('employee'));
    }
    
    /**
     * Show form to edit employee.
     */
    public function edit(Employee $employee)
    {
        // Only admin or the employee themselves can edit
        if (!auth()->user()->is_admin && auth()->id() != $employee->emp_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $departments = Department::all();
        return view('employees.edit', compact('employee', 'departments'));
    }
    
    /**
     * Update employee.
     */
    public function update(Request $request, Employee $employee)
    {
        // Only admin or the employee themselves can update
        if (!auth()->user()->is_admin && auth()->id() != $employee->emp_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->emp_id . ',emp_id',
        ];
        
        // Only admin can change department
        if (auth()->user()->is_admin) {
            $rules['dept_id'] = 'required|exists:departments,dept_id';
        }
        
        // Password validation with confirmation
        if ($request->filled('password')) {
            $rules['password'] = 'required|min:8|confirmed';
            $request->validate($rules);
            $employee->password = Hash::make($request->password);
        } else {
            $request->validate($rules);
        }
        
        // Update fields
        $employee->name = $request->name;
        $employee->email = $request->email;
        
        // Only admin can change department and admin status
        if (auth()->user()->is_admin) {
            $employee->dept_id = $request->dept_id;
            $employee->is_admin = $request->has('is_admin') ? true : false;
        }
        
        $employee->save();
        
        return redirect()->route('employees.index')
            ->with('success', 'Profile updated successfully.');
    }
    
    /**
     * Delete employee (admin only).
     */
    public function destroy(Employee $employee)
    {
        // Only admin can delete employees
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        // Prevent admin from deleting themselves
        if ($employee->emp_id == auth()->id()) {
            return redirect()->route('employees.index')
                ->with('error', 'You cannot delete your own account.');
        }
        
        // Check if employee has assignments
        $assignmentCount = $employee->assignments()->count();
        if ($assignmentCount > 0) {
            return redirect()->route('employees.index')
                ->with('error', "Cannot delete employee with {$assignmentCount} active assignment(s). Please delete all assignments first.");
        }
        
        // Delete the employee
        $employee->delete();
        
        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}