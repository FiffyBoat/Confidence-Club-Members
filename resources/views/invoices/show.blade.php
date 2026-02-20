@extends('layouts.app')

@section('content')
<h2 class="mb-3">Invoice Details</h2>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <p><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</p>
        <p><strong>Payer:</strong> {{ $invoice->payer->full_name ?? $invoice->payer->business_name }}</p>
        <p><strong>Revenue Type:</strong> {{ $invoice->revenueType->name }}</p>
        <p><strong>Amount:</strong> GHS {{ number_format($invoice->amount, 2) }}</p>
        <p><strong>Total Paid:</strong> GHS {{ number_format($invoice->totalPaid(), 2) }}</p>
        <p><strong>Outstanding Balance:</strong> GHS {{ number_format($invoice->outstandingBalance(), 2) }}</p>
        <p><strong>Overpayment:</strong> GHS {{ number_format($invoice->overpaymentAmount(), 2) }}</p>
        <p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>
        <p class="mb-0"><strong>Due Date:</strong> {{ $invoice->due_date }}</p>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white">
        <strong>Payments</strong>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Receipt #</th>
                    <th>Amount Paid</th>
                    <th>Method</th>
                    <th>Collector</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoice->payments as $payment)
                <tr>
                    <td>{{ $payment->receipt_number }}</td>
                    <td>GHS {{ number_format($payment->amount_paid, 2) }}</td>
                    <td>{{ ucfirst($payment->payment_method) }}</td>
                    <td>{{ $payment->collector->name }}</td>
                    <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">No payments recorded yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<a href="{{ route('invoices.print', $invoice->id) }}" class="btn btn-outline-primary mt-3">Print Invoice</a>
<a href="{{ route('invoices.pdf', $invoice->id) }}" class="btn btn-primary mt-3">Download PDF</a>
<a href="{{ route('invoices.index') }}" class="btn btn-secondary mt-3">Back to Invoices</a>
@endsection
