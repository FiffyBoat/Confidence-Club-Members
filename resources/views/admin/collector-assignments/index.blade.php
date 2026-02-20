@extends('admin.layout')

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4 gap-3">
    <div>
        <div class="text-muted small">Admin</div>
        <h2 class="mb-1">Collector Assignments</h2>
        <div class="text-muted">Assign collectors to specific areas.</div>
    </div>
    <a href="{{ route('admin.collector-assignments.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i>New Assignment</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Collector</th>
                    <th>Area</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $assignment)
                <tr>
                    <td>{{ $assignment->collector?->name ?? 'Unknown' }}</td>
                    <td>{{ $assignment->area_name }}</td>
                    <td>{{ $assignment->start_date }}</td>
                    <td>{{ $assignment->end_date ?: '-' }}</td>
                    <td class="text-end">
                        <form method="POST" action="{{ route('admin.collector-assignments.destroy', $assignment->id) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove assignment?')">Remove</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5">No assignments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $assignments->links() }}
</div>
@endsection
