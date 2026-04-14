@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="text-white">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="text-white-50">Here's what's happening with your projects today.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="stat-label">Total Employees</p>
                        <p class="stat-number">{{ \App\Models\Employee::count() }}</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="stat-label">Active Projects</p>
                        <p class="stat-number">{{ \App\Models\Project::count() }}</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="stat-label">Total Assignments</p>
                        <p class="stat-number">{{ \App\Models\Assignment::count() }}</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="stat-label">Total Budget</p>
                        <p class="stat-number">${{ number_format(\App\Models\Project::sum('budget'), 0) }}</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-clock me-2"></i> Recent Assignments
                </div>
                <div class="card-body">
                    @php
                        $recentAssignments = \App\Models\Assignment::with(['employee', 'project'])
                            ->latest()
                            ->take(5)
                            ->get();
                    @endphp
                    @foreach($recentAssignments as $assignment)
                        <div class="mb-3 pb-2 border-bottom">
                            <strong>{{ $assignment->employee->name }}</strong> assigned to 
                            <strong>{{ $assignment->project->title }}</strong>
                            <span class="badge bg-info float-end">{{ $assignment->hours }} hrs/week</span>
                            <div class="text-muted small">Role: {{ $assignment->role }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-simple me-2"></i> Quick Actions
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="{{ route('employees.create') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i> Add New Employee
                        </a>
                        <a href="{{ route('projects.create') }}" class="btn btn-success">
                            <i class="fas fa-plus-circle me-2"></i> Create New Project
                        </a>
                        <a href="{{ route('assignments.create') }}" class="btn btn-info">
                            <i class="fas fa-link me-2"></i> Create New Assignment
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection