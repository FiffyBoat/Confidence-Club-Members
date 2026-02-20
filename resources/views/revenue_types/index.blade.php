@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Revenue Types</h2>
    @if(in_array(auth()->user()->role, ['admin', 'cashier']))
    <a href="{{ route('revenue-types.create') }}" class="btn btn-success">Add Revenue Type</a>
    @endif
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('revenue-types.search') }}" method="GET" class="row g-2 mb-3">
            <div class="col-md-10">
                <input type="text" name="q" class="form-control" placeholder="Search by name or category" value="{{ $query ?? '' }}">
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-primary">Search</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Default Amount</th>
                        <th>Frequency</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($types as $type)
                    <tr>
                        <td>{{ $type->id }}</td>
                        <td>{{ $type->name }}</td>
                        <td>{{ $type->category }}</td>
                        <td>GHS {{ number_format($type->default_amount, 2) }}</td>
                        <td>{{ ucfirst($type->frequency) }}</td>
                        <td class="text-end">
                            @if(in_array(auth()->user()->role, ['admin', 'cashier']))
                            <a href="{{ route('revenue-types.edit', $type->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('revenue-types.destroy', $type->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Delete revenue type?')">Delete</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">No revenue types found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $types->links() }}
</div>
@endsection
