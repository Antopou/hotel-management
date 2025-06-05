<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Folio #{{ $folio->folio_code ?? $folio->id }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: 'Noto Sans', Arial, sans-serif; font-size: 16px; margin: 30px; color: #222;}
        h2 { text-align: center; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px;}
        .table th, .table td { border: 1px solid #bbb; padding: 8px; text-align: left;}
        .table th { background: #f0f0f0; }
        .text-end { text-align: right; }
        .totals { margin-top: 24px; width: 100%; }
        .totals td { font-size: 1.1em; }
    </style>
</head>
<body onload="window.print()">
    <h2>Hotel Guest Folio</h2>
    <hr>
    <table>
        <tr>
            <td><strong>Folio #</strong></td>
            <td>{{ $folio->folio_code ?? $folio->id }}</td>
            <td><strong>Date</strong></td>
            <td>{{ \Carbon\Carbon::parse($folio->created_at)->format('Y-m-d H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Guest</strong></td>
            <td>{{ $folio->guest->name ?? '-' }}</td>
            <td><strong>Room</strong></td>
            <td>{{ $folio->room->name ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Reservation</strong></td>
            <td colspan="3">
                @if($folio->checkin && $folio->checkin->reservation)
                    #{{ $folio->checkin->reservation->reservation_code ?? $folio->checkin->reservation->id }}
                @else
                    -
                @endif
            </td>
        </tr>
    </table>
    <h4 style="margin-top: 32px;">Charges</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th class="text-end">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items->where('type', 'charge') as $charge)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($charge->posted_at)->format('Y-m-d') }}</td>
                    <td>{{ $charge->description }}</td>
                    <td class="text-end">{{ number_format($charge->amount, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center">No charges.</td></tr>
            @endforelse
        </tbody>
    </table>
    <h4 style="margin-top: 32px;">Payments</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Reference</th>
                <th class="text-end">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items->where('type', 'payment') as $payment)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($payment->posted_at)->format('Y-m-d') }}</td>
                    <td>{{ $payment->reference ?? '-' }}</td>
                    <td class="text-end">{{ number_format($payment->amount, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center">No payments.</td></tr>
            @endforelse
        </tbody>
    </table>
    @php
        $totalCharges = $items->where('type', 'charge')->sum('amount');
        $totalPayments = $items->where('type', 'payment')->sum('amount');
        $balance = $totalCharges - $totalPayments;
    @endphp
    <table class="totals">
        <tr>
            <td style="text-align:right;"><strong>Total Charges:</strong></td>
            <td style="text-align:right;">{{ number_format($totalCharges, 2) }}</td>
        </tr>
        <tr>
            <td style="text-align:right;"><strong>Total Payments:</strong></td>
            <td style="text-align:right;">{{ number_format($totalPayments, 2) }}</td>
        </tr>
        <tr>
            <td style="text-align:right;"><strong>Balance:</strong></td>
            <td style="text-align:right;"><strong>{{ number_format($balance, 2) }}</strong></td>
        </tr>
    </table>
    <div style="margin-top: 36px; text-align: center; color: #888;">
        Thank you for staying with us!
    </div>
</body>
</html>
