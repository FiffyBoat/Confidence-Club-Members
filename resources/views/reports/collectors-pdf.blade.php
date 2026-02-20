<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Collector Performance Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Collector Performance Report</h2>
    <p>Date: {{ $day->format('Y-m-d') }}</p>
    <table>
        <thead>
            <tr>
                <th>Collector</th>
                <th>Assigned Areas</th>
                <th>Expected</th>
                <th>Actual</th>
                <th>Variance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($collectorStats as $item)
            <tr>
                <td>{{ $item['collector']->name }}</td>
                <td>{{ $item['assigned_areas']->implode(', ') ?: '-' }}</td>
                <td>{{ number_format($item['expected'], 2) }}</td>
                <td>{{ number_format($item['actual'], 2) }}</td>
                <td>{{ number_format($item['variance'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
