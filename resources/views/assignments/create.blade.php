@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-plus me-2"></i>Create New Assignment
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('assignments.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="emp_id" class="form-label">Employee</label>
                            <select class="form-control @error('emp_id') is-invalid @enderror" 
                                    id="emp_id" name="emp_id" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->emp_id }}" {{ old('emp_id') == $emp->emp_id ? 'selected' : '' }}>
                                        {{ $emp->name }} ({{ $emp->department->name ?? 'No Dept' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('emp_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="proj_id" class="form-label">Project</label>
                            <select class="form-control @error('proj_id') is-invalid @enderror" 
                                    id="proj_id" name="proj_id" required>
                                <option value="">Select Project</option>
                                @foreach($projects as $proj)
                                    <option value="{{ $proj->proj_id }}" {{ old('proj_id') == $proj->proj_id ? 'selected' : '' }}>
                                        {{ $proj->title }} (${{ number_format($proj->budget, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('proj_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <input type="text" class="form-control @error('role') is-invalid @enderror" 
                                   id="role" name="role" value="{{ old('role') }}" 
                                   placeholder="e.g., Developer, Manager, Designer" required>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="hours" class="form-label">Hours per week</label>
                            <input type="number" class="form-control @error('hours') is-invalid @enderror" 
                                   id="hours" name="hours" value="{{ old('hours') }}" 
                                   placeholder="1-168" required>
                            @error('hours')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Create Assignment
                            </button>
                            <a href="{{ route('assignments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection