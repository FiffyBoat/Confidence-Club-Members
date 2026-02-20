@extends('layouts.app')

@section('content')
<h2 class="mb-3">Collector Performance Report</h2>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="date" name="date" class="form-control" value="{{ $day->toDateString() }}">
    </div>
    <div class="col-md-2 d-grid">
        <button class="btn btn-primary">Filter</button>
    </div>
    <div class="col-md-3">
        <a class="btn btn-outline-success w-100" href="{{ route('reports.export', ['type' => 'collectors', 'date' => $day->toDateString(), 'format' => 'xlsx']) }}">Export Excel</a>
    </div>
    <div class="col-md-2 d-grid">
        <a class="btn btn-outline-danger" href="{{ route('reports.export', ['type' => 'collectors', 'date' => $day->toDateString(), 'format' => 'pdf']) }}">Export PDF</a>
    </div>
    <div class="col-md-2 d-grid">
        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">Print / PDF</button>
    </div>
</form>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3">
            <h6 class="text-muted mb-1">Total Collected</h6>
            <h4 class="mb-0">GHS {{ number_format($grandTotal, 2) }}</h4>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><strong>Collectors ({{ $day->toDateString() }})</strong></div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Collector</th>
                    <th>Assigned Areas</th>
                    <th>Phone</th>
                    <th>Expected</th>
                    <th>Actual</th>
                    <th>Variance</th>
                </tr>
            </thead>
            <tbody>
                @forelse($collectorStats as $item)
                <tr>
                    <td>{{ $item['collector']->name }}</td>
                    <td>{{ $item['assigned_areas']->implode(', ') ?: '-' }}</td>
                    <td>{{ $item['collector']->phone ?: '-' }}</td>
                    <td>GHS {{ number_format($item['expected'], 2) }}</td>
                    <td>GHS {{ number_format($item['actual'], 2) }}</td>
                    <td>GHS {{ number_format($item['variance'], 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="6">No collector records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
