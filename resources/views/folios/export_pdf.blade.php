<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Folios Export</title>
    <style>
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #333; padding: 4px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>Folios Export</h2>
    <table>
        <thead>
            <tr>
                <th>Folio Code</th>
                <th>Guest</th>
                <th>Room</th>
                <th>Total</th>
                <th>Paid</th>
                <th>Balance</th>
                <th>Status</th>
                @if(request('include_items'))
                    <th>Items</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($folios as $folio)
            <tr>
                <td>{{ $folio->folio_code }}</td>
                <td>{{ $folio->guest->name ?? '' }}</td>
                <td>{{ $folio->room->name ?? '' }}</td>
                <td>{{ $folio->total_amount }}</td>
                <td>{{ $folio->paid_amount }}</td>
                <td>{{ $folio->balance }}</td>
                <td>{{ $folio->status }}</td>
                @if(request('include_items'))
                    <td>
                        @foreach($folio->items as $item)
                            {{ $item->type }}: {{ $item->description }} ({{ $item->amount }})<br>
                        @endforeach
                    </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>