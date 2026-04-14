@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card">
                <h1 class="display-5 fw-bold mb-2">Welcome back, {{ Auth::user()->name }}! 👋</h1>
                <p class="lead">Here's what's happening with your projects today.</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="stat-label">Total Employees</p>
                        <h2 class="stat-number">{{ $totalEmployees }}</h2>
                        <small class="text-muted">Active team members</small>
                    </div>
                    <div class="stat-icon bg-primary-light">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="stat-label">Active Projects</p>
                        <h2 class="stat-number">{{ $totalProjects }}</h2>
                        <small class="text-muted">Ongoing work</small>
                    </div>
                    <div class="stat-icon bg-success-light">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="stat-label">Total Assignments</p>
                        <h2 class="stat-number">{{ $totalAssignments }}</h2>
                        <small class="text-muted">Task allocations</small>
                    </div>
                    <div class="stat-icon bg-info-light">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="stat-label">Total Budget</p>
                        <h2 class="stat-number">${{ number_format($totalBudget, 0) }}</h2>
                        <small class="text-muted">Project investments</small>
                    </div>
                    <div class="stat-icon bg-warning-light">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-2"></i> Employees by Department
                </div>
                <div class="card-body">
                    <canvas id="departmentChart" width="400" height="300" style="max-width:100%; height:auto;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-2"></i> Top Projects by Budget
                </div>
                <div class="card-body">
                    <canvas id="budgetChart" width="400" height="300" style="max-width:100%; height:auto;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performers & Recent Activity -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-trophy me-2"></i> Top Performing Employees
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @forelse($topEmployees as $employee)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-user-circle me-2 text-primary"></i>
                                    <strong>{{ $employee->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $employee->department->name ?? 'No Dept' }}</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">
                                    {{ $employee->assignments_sum_hours ?? 0 }} hrs
                                </span>
                            </div>
                        @empty
                            <p class="text-muted text-center">No data available</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-clock me-2"></i> Recent Assignments
                </div>
                <div class="card-body">
                    @forelse($recentAssignments as $assignment)
                        <div class="mb-3 pb-2 border-bottom">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <i class="fas fa-user-check text-success me-2"></i>
                                    <strong>{{ $assignment->employee->name }}</strong>
                                    <span class="text-muted">→</span>
                                    <strong>{{ $assignment->project->title }}</strong>
                                </div>
                                <small class="text-muted">{{ $assignment->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="mt-1">
                                <span class="badge bg-info">{{ $assignment->role }}</span>
                                <span class="badge bg-warning">{{ $assignment->hours }} hrs/week</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">No recent assignments</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Project Hours Distribution -->
    @if(count($hoursProjectNames) > 0)
    <div class="row">
        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-line me-2"></i> Project Hours Distribution
                </div>
                <div class="card-body">
                    <canvas id="hoursChart" width="800" height="200" style="max-width:100%; height:auto;"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Department Chart
        const deptData = {
            labels: {!! json_encode($departmentNames) !!},
            datasets: [{
                data: {!! json_encode($departmentCounts) !!},
                backgroundColor: ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899'],
                borderWidth: 0
            }]
        };
        
        const deptCtx = document.getElementById('departmentChart');
        if (deptCtx) {
            new Chart(deptCtx, {
                type: 'doughnut',
                data: deptData,
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Budget Chart
        const budgetData = {
            labels: {!! json_encode($projectNames) !!},
            datasets: [{
                label: 'Budget ($)',
                data: {!! json_encode($projectBudgets) !!},
                backgroundColor: '#4F46E5',
                borderRadius: 8
            }]
        };
        
        const budgetCtx = document.getElementById('budgetChart');
        if (budgetCtx) {
            new Chart(budgetCtx, {
                type: 'bar',
                data: budgetData,
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }

        // Hours Chart
        @if(count($hoursProjectNames) > 0)
        const hoursData = {
            labels: {!! json_encode($hoursProjectNames) !!},
            datasets: [{
                label: 'Total Hours',
                data: {!! json_encode($hoursProjectTotals) !!},
                borderColor: '#4F46E5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                tension: 0.4,
                fill: true
            }]
        };
        
        const hoursCtx = document.getElementById('hoursChart');
        if (hoursCtx) {
            new Chart(hoursCtx, {
                type: 'line',
                data: hoursData,
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        }
        @endif
    });
</script>
@endsection