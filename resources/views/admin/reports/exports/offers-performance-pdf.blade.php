<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Offers Performance Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
        }

        h1, h3 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #444;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #eee;
        }
    </style>
</head>
<body>

    <h1>Sofra</h1>
    <h3>Offers Performance Report</h3>

    <p><strong>Generated At:</strong> {{ now()->format('Y-m-d H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>Offer</th>
                <th>Active Period</th>
                <th>Items</th>
                <th>Used</th>
                <th>Discount</th>
                <th>Revenue</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @foreach($offerStats as $stat)
                <tr>
                    <td>{{ $stat['offer']->name }}</td>
                    <td>
                        {{ $stat['offer']->start_date ?? '-' }}
                        -
                        {{ $stat['offer']->end_date ?? '-' }}
                    </td>
                    <td>{{ $stat['items_count'] }}</td>
                    <td>{{ $stat['times_used'] }}</td>
                    <td>{{ number_format($stat['total_discount'], 2) }}</td>
                    <td>{{ number_format($stat['revenue'], 2) }}</td>
                    <td>{{ $stat['offer']->is_active ? 'Active' : 'Inactive' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>