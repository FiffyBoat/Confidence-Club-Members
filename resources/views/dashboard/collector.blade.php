@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Revenue Officer Dashboard</h2>
    <a href="{{ route('payments.create') }}" class="btn btn-primary">Quick Payment Entry</a>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3">
            <h6 class="text-muted mb-1">My Collections Today</h6>
            <h4 class="mb-0">GHS {{ number_format($myCollectionsToday, 2) }}</h4>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm p-3">
            <h6 class="text-muted mb-2">Assigned Areas</h6>
            @if($assignedAreas->isEmpty())
            <div class="text-muted">No active area assignments.</div>
            @else
            <div>{{ $assignedAreas->implode(', ') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><strong>My Payments Today</strong></div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Receipt</th>
                    <th>Invoice</th>
                    <th>Method</th>
                    <th>Amount</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($myPaymentsToday as $payment)
                <tr>
                    <td>{{ $payment->receipt_number }}</td>
                    <td>{{ $payment->invoice?->invoice_number }}</td>
                    <td>{{ ucfirst($payment->payment_method) }}</td>
                    <td>GHS {{ number_format($payment->amount_paid, 2) }}</td>
                    <td>{{ $payment->created_at->format('H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">No payments recorded today.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
