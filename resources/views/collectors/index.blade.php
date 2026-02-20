@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Collectors</h2>
    <a href="{{ route('collectors.create') }}" class="btn btn-success">Add Collector</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($collectors as $collector)
                    <tr>
                        <td>{{ $collector->id }}</td>
                        <td>{{ $collector->name }}</td>
                        <td>{{ $collector->email }}</td>
                        <td>{{ $collector->phone }}</td>
                        <td class="text-end">
                            <a href="{{ route('collectors.edit', $collector->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('collectors.destroy', $collector->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Delete collector?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">No collectors found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $collectors->links() }}
</div>
@endsection
