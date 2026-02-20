@extends('layouts.app')

@section('content')
<h2 class="mb-3">Monthly Revenue Summary</h2>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="month" name="month" class="form-control" value="{{ $selectedMonth }}">
    </div>
    <div class="col-md-2 d-grid">
        <button class="btn btn-primary">Filter</button>
    </div>
    <div class="col-md-3">
        <a class="btn btn-outline-success w-100" href="{{ route('reports.export', ['type' => 'monthly', 'month' => $selectedMonth, 'format' => 'xlsx']) }}">Export Excel</a>
    </div>
    <div class="col-md-2 d-grid">
        <a class="btn btn-outline-danger" href="{{ route('reports.export', ['type' => 'monthly', 'month' => $selectedMonth, 'format' => 'pdf']) }}">Export PDF</a>
    </div>
    <div class="col-md-2 d-grid">
        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">Print / PDF</button>
    </div>
</form>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3">
            <h6 class="text-muted mb-1">Total Revenue</h6>
            <h4 class="mb-0">GHS {{ number_format($total, 2) }}</h4>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white"><strong>By Revenue Type</strong></div>
    <div class="table-responsive">
        <table class="table table-sm mb-0">
            <thead><tr><th>Revenue Type</th><th>Amount</th></tr></thead>
            <tbody>
                @forelse($byRevenueType as $type => $amount)
                <tr><td>{{ $type }}</td><td>GHS {{ number_format($amount, 2) }}</td></tr>
                @empty
                <tr><td colspan="2">No data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><strong>Payments</strong></div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Receipt</th>
                    <th>Invoice</th>
                    <th>Collector</th>
                    <th>Method</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                    <td>{{ $payment->receipt_number }}</td>
                    <td>{{ $payment->invoice?->invoice_number }}</td>
                    <td>{{ $payment->collector?->name ?? '-' }}</td>
                    <td>{{ ucfirst($payment->payment_method) }}</td>
                    <td>GHS {{ number_format($payment->amount_paid, 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="6">No payments for selected month.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
