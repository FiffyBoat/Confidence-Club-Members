@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Payers</h2>
    @if(in_array(auth()->user()->role, ['admin', 'cashier']))
    <a href="{{ route('payers.create') }}" class="btn btn-success">Add Payer</a>
    @endif
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('payers.search') }}" method="GET" class="row g-2 mb-3">
            <div class="col-md-10">
                <input type="text" name="q" class="form-control" placeholder="Search by name, phone, area" value="{{ $query ?? '' }}">
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
                        <th>Name / Business</th>
                        <th>Phone</th>
                        <th>Electoral Area</th>
                        <th>Type</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payers as $payer)
                    <tr>
                        <td>{{ $payer->id }}</td>
                        <td>{{ $payer->full_name ?? $payer->business_name }}</td>
                        <td>{{ $payer->phone }}</td>
                        <td>{{ $payer->electoral_area }}</td>
                        <td>{{ ucfirst($payer->payer_type) }}</td>
                        <td class="text-end">
                            <a href="{{ route('payers.show', $payer->id) }}" class="btn btn-info btn-sm">History</a>
                            @if(in_array(auth()->user()->role, ['admin', 'cashier']))
                            <a href="{{ route('payers.edit', $payer->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('payers.destroy', $payer->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Delete payer?')">Delete</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">No payers found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $payers->links() }}
</div>
@endsection
