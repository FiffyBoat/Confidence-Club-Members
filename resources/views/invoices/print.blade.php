<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h3 class="mb-1">Confidence Club Members</h3>
                <div>Revenue Invoice</div>
            </div>
            <div class="text-end">
                <div><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</div>
                <div><strong>Date:</strong> {{ $invoice->created_at->format('Y-m-d') }}</div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Payer:</strong> {{ $invoice->payer->full_name ?? $invoice->payer->business_name }}<br>
                <strong>Phone:</strong> {{ $invoice->payer->phone }}<br>
                <strong>Location:</strong> {{ $invoice->payer->location }}
            </div>
            <div class="col-md-6">
                <strong>Revenue Type:</strong> {{ $invoice->revenueType->name }}<br>
                <strong>Due Date:</strong> {{ $invoice->due_date }}<br>
                <strong>Status:</strong> {{ ucfirst($invoice->status) }}
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-end">Amount (GHS)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $invoice->revenueType->name }} Invoice</td>
                    <td class="text-end">{{ number_format($invoice->amount, 2) }}</td>
                </tr>
                <tr>
                    <td>Total Paid</td>
                    <td class="text-end">{{ number_format($invoice->totalPaid(), 2) }}</td>
                </tr>
                <tr>
                    <td>Outstanding Balance</td>
                    <td class="text-end">{{ number_format($invoice->outstandingBalance(), 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="mt-5">
            <div>Authorized Signature: ________________________</div>
        </div>
    </div>

    <script>window.print();</script>
</body>
</html>
