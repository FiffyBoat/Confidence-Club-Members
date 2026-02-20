@extends('layouts.app')

@section('content')
<h2 class="mb-3">Payer History</h2>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <p><strong>Name / Business:</strong> {{ $payer->full_name ?? $payer->business_name }}</p>
        <p><strong>Phone:</strong> {{ $payer->phone }}</p>
        <p class="mb-0"><strong>Electoral Area:</strong> {{ $payer->electoral_area }}</p>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white">
        <strong>Invoices</strong>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Revenue Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Payments</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->invoice_number }}</td>
                    <td>{{ $invoice->revenueType->name }}</td>
                    <td>GHS {{ number_format($invoice->amount, 2) }}</td>
                    <td>{{ ucfirst($invoice->status) }}</td>
                    <td>
                        @forelse($invoice->payments as $payment)
                        <div>{{ $payment->receipt_number }} - GHS {{ number_format($payment->amount_paid, 2) }}</div>
                        @empty
                        <span class="text-muted">No payments</span>
                        @endforelse
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">No invoice history available.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<a href="{{ route('payers.index') }}" class="btn btn-secondary mt-3">Back to Payers</a>
@endsection
