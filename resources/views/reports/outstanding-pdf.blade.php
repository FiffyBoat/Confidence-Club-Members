<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Outstanding Bills Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Outstanding Bills Report</h2>
    <p>As of: {{ $asOfDate->format('Y-m-d') }}</p>
    <table>
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>Payer</th>
                <th>Revenue Type</th>
                <th>Total</th>
                <th>Paid</th>
                <th>Outstanding</th>
                <th>Due Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
            <tr>
                <td>{{ $invoice->invoice_number }}</td>
                <td>{{ $invoice->payer?->full_name ?? $invoice->payer?->business_name }}</td>
                <td>{{ $invoice->revenueType?->name }}</td>
                <td>{{ number_format($invoice->amount, 2) }}</td>
                <td>{{ number_format($invoice->totalPaid(), 2) }}</td>
                <td>{{ number_format($invoice->outstandingBalance(), 2) }}</td>
                <td>{{ $invoice->due_date }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
