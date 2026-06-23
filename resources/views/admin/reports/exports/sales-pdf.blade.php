<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report</title>

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

        .summary {
            margin-bottom: 20px;
        }

        .summary p {
            margin: 4px 0;
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

    <h1>Restaurant System</h1>
    <h3>Sales Report</h3>

    <div class="summary">
        <p><strong>Total Sales:</strong> {{ number_format($totalSales, 2) }} EGP</p>
        <p><strong>Paid Bills:</strong> {{ $completedOrdersCount }}</p>
        <p><strong>Average Bill Value:</strong> {{ number_format($averageOrderValue, 2) }} EGP</p>
        <p><strong>Generated At:</strong> {{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Bill #</th>
                <th>Order #</th>
                <th>Table</th>
                <th>Waiter</th>
                <th>Cashier</th>
                <th>Payment</th>
                <th>Total</th>
                <th>Paid At</th>
            </tr>
        </thead>

        <tbody>
            @foreach($bills as $bill)
                <tr>
                    <td>{{ $bill->bill_number }}</td>
                    <td>#{{ $bill->order_id }}</td>
                    <td>{{ $bill->order->table->table_number ?? 'Takeaway' }}</td>
                    <td>{{ $bill->order->waiter->name ?? '-' }}</td>
                    <td>{{ $bill->cashier->name ?? '-' }}</td>
                    <td>{{ ucfirst($bill->payment_method) }}</td>
                    <td>{{ number_format($bill->grand_total, 2) }}</td>
                    <td>{{ $bill->paid_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>