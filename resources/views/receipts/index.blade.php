@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4 gap-3">
    <div>
        <div class="text-muted small">Records</div>
        <h2 class="mb-1">Receipts</h2>
        <div class="text-muted">View generated receipts and download PDF copies.</div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('receipts.index') }}" method="GET" class="row g-2 mb-3">
            <div class="col-md-10">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" class="form-control" placeholder="Search by receipt or member" value="{{ $search ?? '' }}">
                    @if(!empty($search))
                        <a href="{{ route('receipts.index') }}" class="btn btn-outline-secondary">Clear</a>
                    @endif
                </div>
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
                        <th>Member</th>
                        <th>Amount</th>
                        <th>Generated</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($receipts as $receipt)
                    <tr>
                        <td>{{ $receipt->receipt_number }}</td>
                        <td>{{ $receipt->member->full_name ?? 'General Income' }}</td>
                        <td>GHS {{ number_format($receipt->amount, 2) }}</td>
                        <td>{{ $receipt->created_at->format('Y-m-d') }}</td>
                        <td class="text-end">
                            <a href="{{ route('receipts.view', $receipt) }}" class="btn btn-primary btn-sm" target="_blank">View / Print</a>
                            <a href="{{ route('receipts.show', $receipt) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('receipts.download', $receipt) }}" class="btn btn-outline-primary btn-sm">Download</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">No receipts found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $receipts->links() }}
</div>
@endsection
