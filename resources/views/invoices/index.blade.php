@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Invoices</h2>
    @if(in_array(auth()->user()->role, ['admin', 'cashier']))
    <a href="{{ route('invoices.create') }}" class="btn btn-success">Create Invoice</a>
    @endif
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('invoices.search') }}" method="GET" class="row g-2 mb-3">
            <div class="col-md-10">
                <input type="text" name="q" class="form-control" placeholder="Search by invoice, payer, or type" value="{{ $query ?? '' }}">
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-primary">Search</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Payer</th>
                        <th>Revenue Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->payer->full_name ?? $invoice->payer->business_name }}</td>
                        <td>{{ $invoice->revenueType->name }}</td>
                        <td>GHS {{ number_format($invoice->amount, 2) }}</td>
                        <td>{{ ucfirst($invoice->status) }}</td>
                        <td>{{ $invoice->due_date }}</td>
                        <td class="text-end">
                            <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info btn-sm">View</a>
                            @if(in_array(auth()->user()->role, ['admin', 'cashier']))
                            <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Delete invoice?')">Delete</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">No invoices found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $invoices->links() }}
</div>
@endsection
