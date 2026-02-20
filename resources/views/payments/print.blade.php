<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Receipt {{ $payment->receipt_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h3 class="mb-1">Confidence Club Members</h3>
                <div>Official Payment Receipt</div>
            </div>
            <div class="text-end">
                <div><strong>Receipt #:</strong> {{ $payment->receipt_number }}</div>
                <div><strong>Date:</strong> {{ $payment->created_at->format('Y-m-d') }}</div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Invoice #:</strong> {{ $payment->invoice->invoice_number }}<br>
                <strong>Collector:</strong> {{ $payment->collector->name ?? '-' }}<br>
                <strong>Payment Method:</strong> {{ ucfirst($payment->payment_method) }}
            </div>
            <div class="col-md-6">
                <strong>Amount Paid:</strong> GHS {{ number_format($payment->amount_paid, 2) }}<br>
                <strong>Invoice Outstanding:</strong> GHS {{ number_format($payment->invoice->outstandingBalance(), 2) }}<br>
                <strong>Invoice Overpayment:</strong> GHS {{ number_format($payment->invoice->overpaymentAmount(), 2) }}
            </div>
        </div>

        <div class="border p-3 mb-5">
            <strong>Official Stamp Area</strong>
            <div style="height: 80px;"></div>
        </div>

        <div>Authorized Signature: ________________________</div>
    </div>

    <script>window.print();</script>
</body>
</html>
