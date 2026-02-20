<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Monthly Revenue Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Monthly Revenue Summary</h2>
    <p>Month: {{ $selectedMonth }}</p>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Receipt</th>
                <th>Invoice</th>
                <th>Collector</th>
                <th>Method</th>
                <th>Amount (GHS)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                <td>{{ $payment->receipt_number }}</td>
                <td>{{ $payment->invoice?->invoice_number }}</td>
                <td>{{ $payment->collector?->name ?? '-' }}</td>
                <td>{{ ucfirst($payment->payment_method) }}</td>
                <td>{{ number_format($payment->amount_paid, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
