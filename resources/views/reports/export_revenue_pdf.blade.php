<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Revenue Report</title>
    <style>
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #333; padding: 4px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>Revenue Report</h2>
    <table>
        <thead>
            <tr>
                <th>Date / Month</th>
                <th>Room Revenue</th>
                <th>F&amp;B Revenue</th>
                <th>Other Revenue</th>
                <th>Total Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($revenueDetails as $detail)
            <tr>
                <td>{{ $detail['date_or_month'] }}</td>
                <td>{{ $detail['room_revenue'] }}</td>
                <td>{{ $detail['fb_revenue'] }}</td>
                <td>{{ $detail['other_revenue'] }}</td>
                <td>{{ $detail['total_revenue'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>