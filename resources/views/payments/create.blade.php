@extends('layouts.app')

@section('content')
<h2 class="mb-3">Record Payment</h2>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('payments.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Invoice</label>
                <select name="invoice_id" class="form-select" required>
                    @foreach($invoices as $invoice)
                    <option value="{{ $invoice->id }}" {{ old('invoice_id') == $invoice->id ? 'selected' : '' }}>
                        {{ $invoice->invoice_number }} - GHS {{ number_format($invoice->amount, 2) }} - {{ ucfirst($invoice->status) }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Amount Paid</label>
                <input type="number" name="amount_paid" class="form-control" step="0.01" min="0.01" value="{{ old('amount_paid') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Payment Method</label>
                <select name="payment_method" class="form-select" required>
                    <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="momo" {{ old('payment_method') === 'momo' ? 'selected' : '' }}>Mobile Money</option>
                    <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
                    <option value="bank" {{ old('payment_method') === 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Collector</label>
                <select name="collector_id" class="form-select" required>
                    @foreach($collectors as $collector)
                    <option value="{{ $collector->id }}" {{ old('collector_id') == $collector->id ? 'selected' : '' }}>
                        {{ $collector->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-primary">Record Payment</button>
        </form>
    </div>
</div>
@endsection
