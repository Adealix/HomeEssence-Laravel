<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin: 0 auto; width: 80%; }
        .details table { width: 100%; border-collapse: collapse; }
        .details th, .details td { border: 1px solid #ddd; padding: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Receipt</h1>
        <p>Order ID: {{ $order->orderinfo_id }}</p>
    </div>
    <div class="details">
        <p><strong>Customer:</strong> {{ $order->customer->fname }} {{ $order->customer->lname }}</p>
        <p><strong>Date Placed:</strong> {{ $order->date_placed }}</p>
        <p><strong>Status:</strong> {{ $order->status }}</p>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderlines as $ol)
                    <tr>
                        <td>{{ $ol->item->name }}</td>
                        <td>{{ $ol->quantity }}</td>
                        <td>${{ number_format($ol->item->sell_price * $ol->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p><strong>Total Price:</strong> ${{ number_format($order->orderlines->sum(function($ol) { return $ol->quantity * $ol->item->sell_price; }) + $order->shipping, 2) }}</p>
    </div>
</body>
</html>