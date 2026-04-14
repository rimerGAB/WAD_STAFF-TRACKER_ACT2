@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle me-2"></i>Employee Details
                    </h5>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>

                <div class="card-body">
                    <!-- Employee Information -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="text-center mb-4">
                                <i class="fas fa-user-circle" style="font-size: 80px; color: #4F46E5;"></i>
                            </div>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%" class="bg-light">Employee ID</th>
                                    <td>{{ $employee->emp_id }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Full Name</th>
                                    <td>{{ $employee->name }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Email Address</th>
                                    <td>{{ $employee->email }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Department</th>
                                    <td>
                                        <i class="fas fa-building me-1"></i>
                                        {{ $employee->department->name ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Role</th>
                                    <td>
                                        @if($employee->is_admin)
                                            <span class="badge bg-danger">
                                                <i class="fas fa-crown me-1"></i> Administrator
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-user me-1"></i> Regular Employee
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Member Since</th>
                                    <td>
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ $employee->created_at->format('F d, Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Last Updated</th>
                                    <td>
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $employee->updated_at->format('F d, Y H:i') }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Project Assignments Section -->
                    <h5 class="mt-4 mb-3">
                        <i class="fas fa-tasks me-2"></i>My Project Assignments
                    </h5>
                    
                    @if($employee->assignments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Project</th>
                                        <th>Role</th>
                                        <th>Hours/Week</th>
                                        <th>Assigned Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employee->assignments as $assignment)
                                        <tr>
                                            <td>
                                                <i class="fas fa-project-diagram me-1 text-primary"></i>
                                                {{ $assignment->project->title ?? 'N/A' }}
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $assignment->role }}</span>
                                            </td>
                                            <td>
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $assignment->hours }} hours
                                            </td>
                                            <td>{{ $assignment->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('assignments.show', $assignment->assign_id) }}" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No project assignments yet.
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    @if(auth()->user()->is_admin || auth()->id() == $employee->emp_id)
                        <div class="mt-4 text-center">
                            <a href="{{ route('employees.edit', $employee->emp_id) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i> Edit Profile
                            </a>
                            
                            @if(auth()->user()->is_admin && auth()->id() != $employee->emp_id)
                                <form action="{{ route('employees.destroy', $employee->emp_id) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('Are you sure you want to delete this employee?')">
                                        <i class="fas fa-trash me-1"></i> Delete Employee
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection`