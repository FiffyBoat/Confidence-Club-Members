@extends('layouts.app')

@section('content')
<h2 class="mb-3">Edit Invoice</h2>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Payer</label>
                <select name="payer_id" class="form-select" required>
                    @foreach($payers as $payer)
                    <option value="{{ $payer->id }}" {{ old('payer_id', $invoice->payer_id) == $payer->id ? 'selected' : '' }}>
                        {{ $payer->full_name ?? $payer->business_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Revenue Type</label>
                <select name="revenue_type_id" class="form-select" required>
                    @foreach($revenueTypes as $type)
                    <option value="{{ $type->id }}" {{ old('revenue_type_id', $invoice->revenue_type_id) == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Amount</label>
                <input type="number" step="0.01" min="0" name="amount" class="form-control" value="{{ old('amount', $invoice->amount) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Due Date</label>
                <input type="date" name="due_date" class="form-control" value="{{ old('due_date', $invoice->due_date) }}" required>
            </div>

            <button class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@endsection
