@extends('layouts.app')

@section('content')
<h2 class="mb-3">Edit Revenue Type</h2>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('revenue-types.update', $revenueType->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $revenueType->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <input type="text" name="category" class="form-control" value="{{ old('category', $revenueType->category) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Default Amount</label>
                <input type="number" name="default_amount" class="form-control" step="0.01" min="0" value="{{ old('default_amount', $revenueType->default_amount) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Frequency</label>
                <select name="frequency" class="form-select" required>
                    <option value="one-time" {{ old('frequency', $revenueType->frequency) === 'one-time' ? 'selected' : '' }}>One-Time</option>
                    <option value="monthly" {{ old('frequency', $revenueType->frequency) === 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="yearly" {{ old('frequency', $revenueType->frequency) === 'yearly' ? 'selected' : '' }}>Yearly</option>
                </select>
            </div>

            <button class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@endsection
