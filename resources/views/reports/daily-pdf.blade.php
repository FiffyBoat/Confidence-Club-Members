<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Daily Revenue Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Daily Revenue Report</h2>
    <p>Date: {{ $day->format('Y-m-d') }}</p>
    <table>
        <thead>
            <tr>
                <th>Time</th>
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
                <td>{{ $payment->created_at->format('H:i') }}</td>
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
