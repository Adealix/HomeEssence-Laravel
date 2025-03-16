<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
    <h1>Order Confirmation</h1>
    <p>Thank you for your purchase!</p>
    <p>Order ID: {{ $order->orderinfo_id }}</p>
    <p>Date Placed: {{ $order->date_placed }}</p>
    <p>Shipping Date: {{ $order->date_shipped }}</p>
    <p>Shipping Cost: ${{ $order->shipping }}</p>
    <p>Status: {{ $order->status }}</p>
    <h2>Order Details</h2>
    <ul>
        @foreach ($cart->items as $item)
            <li>Quantity: {{ $item['qty'] }} - {{ $item['item']->name }} - Price: ${{ $item['price'] }}</li>
        @endforeach
    </ul>
    <p>Total Price: ${{ $cart->totalPrice + $order->shipping}}</p>
</body>
</html>