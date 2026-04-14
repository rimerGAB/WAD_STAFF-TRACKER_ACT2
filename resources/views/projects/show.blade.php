@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Project Details</h5>
                    <a href="{{ route('projects.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">ID</th>
                            <td>{{ $project->proj_id }}</td>
                        </tr>
                        <tr>
                            <th>Title</th>
                            <td>{{ $project->title }}</td>
                        </tr>
                        <tr>
                            <th>Budget</th>
                            <td>${{ number_format($project->budget, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Created Date</th>
                            <td>{{ $project->created_at->format('F d, Y H:i') }}</td>
                        </tr>
                    </table>

                    <h5 class="mt-4">Assigned Employees</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Department</th>
                                    <th>Role</th>
                                    <th>Hours</th>
                                    <th>Assigned Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($project->assignments as $assignment)
                                    <tr>
                                        <td>{{ $assignment->employee->name ?? 'N/A' }}</td>
                                        <td>{{ $assignment->employee->department->name ?? 'N/A' }}</td>
                                        <td>{{ $assignment->role }}</td>
                                        <td>{{ $assignment->hours }} hrs</td>
                                        <td>{{ $assignment->created_at->format('F d, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No employees assigned to this project yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(auth()->user()->is_admin)
                        <div class="mt-3">
                            <a href="{{ route('projects.edit', $project->proj_id) }}" class="btn btn-warning">Edit Project</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection