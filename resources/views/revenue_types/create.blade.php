@extends('layouts.app')

@section('content')
<h2 class="mb-3">Add Revenue Type</h2>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('revenue-types.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <input type="text" name="category" class="form-control" value="{{ old('category') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Default Amount</label>
                <input type="number" name="default_amount" class="form-control" step="0.01" min="0" value="{{ old('default_amount') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Frequency</label>
                <select name="frequency" class="form-select" required>
                    <option value="one-time" {{ old('frequency') === 'one-time' ? 'selected' : '' }}>One-Time</option>
                    <option value="monthly" {{ old('frequency') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="yearly" {{ old('frequency') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                </select>
            </div>

            <button class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
@endsection
