@extends('layouts.app')

@section('content')
<h2 class="mb-3">Payment Receipt</h2>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <p><strong>Receipt #:</strong> {{ $payment->receipt_number }}</p>
        <p><strong>Invoice:</strong> {{ $payment->invoice->invoice_number }}</p>
        <p><strong>Amount Paid:</strong> GHS {{ number_format($payment->amount_paid, 2) }}</p>
        <p><strong>Invoice Amount:</strong> GHS {{ number_format($payment->invoice->amount, 2) }}</p>
        <p><strong>Invoice Outstanding:</strong> GHS {{ number_format($payment->invoice->outstandingBalance(), 2) }}</p>
        <p><strong>Invoice Overpayment:</strong> GHS {{ number_format($payment->invoice->overpaymentAmount(), 2) }}</p>
        <p><strong>Payment Method:</strong> {{ ucfirst($payment->payment_method) }}</p>
        <p><strong>Collector:</strong> {{ $payment->collector->name }}</p>
        <p class="mb-0"><strong>Date:</strong> {{ $payment->created_at->format('Y-m-d') }}</p>
    </div>
</div>

<a href="{{ route('payments.print', $payment->id) }}" class="btn btn-outline-primary mt-3">Print Receipt</a>
<a href="{{ route('payments.pdf', $payment->id) }}" class="btn btn-primary mt-3">Download PDF</a>
<a href="{{ route('payments.index') }}" class="btn btn-secondary mt-3">Back to Payments</a>
@endsection
