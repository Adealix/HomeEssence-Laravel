<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Update</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        h1, h2 { color: #333; }
        p { margin: 0 0 10px; }
        ul { list-style: none; padding: 0; }
        li { margin-bottom: 5px; }
    </style>
</head>
<body>
    <h1>Order Update</h1>
    <p>Your order has been updated!</p>
    <p><strong>Order ID:</strong> {{ $order->orderinfo_id }}</p>
    <p><strong>Date Placed:</strong> {{ $order->date_placed }}</p>
    <p><strong>Shipping Date:</strong> {{ $order->date_shipped }}</p>
    <p><strong>Shipping Cost:</strong> ${{ $order->shipping }}</p>
    <p><strong>Status:</strong> {{ $order->status }}</p>
    <h2>Order Details</h2>
    @if ($order->orderlines && $order->orderlines->count() > 0)
        <ul>
            @foreach ($order->orderlines as $orderline)
                <li>
                    Quantity: {{ $orderline->quantity }} - {{ $orderline->item->name }} - Price: ${{ number_format($orderline->item->sell_price * $orderline->quantity, 2) }}
                </li>
            @endforeach
        </ul>
        <p>
            <strong>Total Price:</strong> ${{ number_format($order->orderlines->sum(function($ol) {
                return $ol->item->sell_price * $ol->quantity;
            }) + $order->shipping, 2) }}
        </p>
    @else
        <p>Order details are not available.</p>
    @endif
</body>
</html>
