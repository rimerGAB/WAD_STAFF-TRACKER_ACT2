@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-tasks me-2"></i>Assignment Details
                    </h5>
                    <a href="{{ route('assignments.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">ID</th>
                            <td>{{ $assignment->assign_id }}</td>
                        </tr>
                        <tr>
                            <th>Employee</th>
                            <td>
                                <i class="fas fa-user me-1"></i>
                                {{ $assignment->employee->name }}<br>
                                <small class="text-muted">
                                    <i class="fas fa-building me-1"></i>
                                    {{ $assignment->employee->department->name ?? 'No Department' }}
                                </small>
                            </td>
                        </tr>
                        <tr>
                            <th>Project</th>
                            <td>
                                <i class="fas fa-project-diagram me-1"></i>
                                {{ $assignment->project->title }}<br>
                                <small class="text-muted">
                                    <i class="fas fa-dollar-sign me-1"></i>
                                    Budget: ${{ number_format($assignment->project->budget, 2) }}
                                </small>
                            </td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td>
                                <span class="badge bg-info">{{ $assignment->role }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Hours per week</th>
                            <td>{{ $assignment->hours }} hours</td>
                        </tr>
                        <tr>
                            <th>Created Date</th>
                            <td>{{ $assignment->created_at->format('F d, Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated</th>
                            <td>{{ $assignment->updated_at->format('F d, Y H:i') }}</td>
                        </tr>
                    </table>

                    @if(auth()->user()->is_admin)
                        <div class="mt-3 text-center">
                            <a href="{{ route('assignments.edit', $assignment->assign_id) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i> Edit Assignment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection