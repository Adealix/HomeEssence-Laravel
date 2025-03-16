<!DOCTYPE html>
<html>
<head>
    <title>Order Update</title>
</head>
<body>
    <h1>Order Update</h1>
    <p>Your order has been updated!</p>
    <p>Order ID: {{ $order->orderinfo_id }}</p>
    <p>Date Placed: {{ $order->date_placed }}</p>
    <p>Shipping Date: {{ $order->date_shipped }}</p>
    <p>Shipping Cost: ${{ $order->shipping }}</p>
    <p>Status: {{ $order->status }}</p>
    <h2>Order Details</h2>
    @if ($order->orderlines && $order->orderlines->count() > 0)
        <ul>
            @foreach ($order->orderlines as $orderline)
                <li>Quantity: {{ $orderline->quantity }} - {{ $orderline->item->name }} - Price: ${{ number_format($orderline->item->sell_price * $orderline->quantity, 2) }}</li>
            @endforeach
        </ul>
        <p>
            Total Price: ${{ number_format($order->orderlines->sum(function($ol) {
                return $ol->item->sell_price * $ol->quantity;
            }) + $order->shipping, 2) }}
        </p>
    @else
        <p>Order details are not available.</p>
    @endif
</body>
</html>