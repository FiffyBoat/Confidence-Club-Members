@extends('admin.layout')

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4 gap-3">
    <div>
        <div class="text-muted small">Admin</div>
        <h2 class="mb-1">Create Collector Assignment</h2>
        <div class="text-muted">Assign a collector to an area and timeframe.</div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.collector-assignments.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Collector</label>
                <select name="collector_id" class="form-select" required>
                    <option value="">Select collector</option>
                    @foreach($collectors as $collector)
                    <option value="{{ $collector->id }}" {{ old('collector_id') == $collector->id ? 'selected' : '' }}>
                        {{ $collector->name }} ({{ $collector->email }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Area Name</label>
                <input type="text" name="area_name" class="form-control" value="{{ old('area_name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ old('start_date', now()->toDateString()) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">End Date (optional)</label>
                <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
            </div>

            <button class="btn btn-primary">Save Assignment</button>
            <a href="{{ route('admin.collector-assignments.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
