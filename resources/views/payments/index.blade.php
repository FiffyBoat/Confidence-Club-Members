@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Payments</h2>
    @if(in_array(auth()->user()->role, ['admin', 'cashier']))
    <a href="{{ route('payments.create') }}" class="btn btn-success">Record Payment</a>
    @endif
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('payments.search') }}" method="GET" class="row g-2 mb-3">
            <div class="col-md-10">
                <input type="text" name="q" class="form-control" placeholder="Search receipt or invoice" value="{{ $query ?? '' }}">
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-primary">Search</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Receipt #</th>
                        <th>Invoice</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Collector</th>
                        <th>Date</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td>{{ $payment->receipt_number }}</td>
                        <td>{{ $payment->invoice->invoice_number }}</td>
                        <td>GHS {{ number_format($payment->amount_paid, 2) }}</td>
                        <td>{{ ucfirst($payment->payment_method) }}</td>
                        <td>{{ $payment->collector->name }}</td>
                        <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                        <td class="text-end">
                            <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-info btn-sm">View</a>
                            @if(in_array(auth()->user()->role, ['admin', 'cashier']))
                            <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Delete payment?')">Delete</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">No payments found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $payments->links() }}
</div>
@endsection
