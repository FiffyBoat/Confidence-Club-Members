@extends('layouts.app')

@section('content')
<h2 class="mb-3">Outstanding Bills Report</h2>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="date" name="as_of" class="form-control" value="{{ $asOfDate->toDateString() }}">
    </div>
    <div class="col-md-2 d-grid">
        <button class="btn btn-primary">Filter</button>
    </div>
    <div class="col-md-3">
        <a class="btn btn-outline-success w-100" href="{{ route('reports.export', ['type' => 'outstanding', 'as_of' => $asOfDate->toDateString(), 'format' => 'xlsx']) }}">Export Excel</a>
    </div>
    <div class="col-md-2 d-grid">
        <a class="btn btn-outline-danger" href="{{ route('reports.export', ['type' => 'outstanding', 'as_of' => $asOfDate->toDateString(), 'format' => 'pdf']) }}">Export PDF</a>
    </div>
    <div class="col-md-2 d-grid">
        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">Print / PDF</button>
    </div>
</form>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3">
            <h6 class="text-muted mb-1">Total Outstanding</h6>
            <h4 class="mb-0">GHS {{ number_format($totalOutstanding, 2) }}</h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3">
            <h6 class="text-muted mb-1">Overdue Invoices</h6>
            <h4 class="mb-0">{{ $overdue->count() }}</h4>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><strong>Outstanding Invoices</strong></div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Payer</th>
                    <th>Revenue Type</th>
                    <th>Total</th>
                    <th>Paid</th>
                    <th>Outstanding</th>
                    <th>Due Date</th>
                    <th>Overdue</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->invoice_number }}</td>
                    <td>{{ $invoice->payer?->full_name ?? $invoice->payer?->business_name }}</td>
                    <td>{{ $invoice->revenueType?->name }}</td>
                    <td>GHS {{ number_format($invoice->amount, 2) }}</td>
                    <td>GHS {{ number_format($invoice->totalPaid(), 2) }}</td>
                    <td>GHS {{ number_format($invoice->outstandingBalance(), 2) }}</td>
                    <td>{{ $invoice->due_date }}</td>
                    <td>
                        @if(\Carbon\Carbon::parse($invoice->due_date)->lt($asOfDate))
                        <span class="badge text-bg-danger">Yes</span>
                        @else
                        <span class="badge text-bg-secondary">No</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8">No outstanding invoices.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
