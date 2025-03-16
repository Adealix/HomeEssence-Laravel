<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        h1, h2 { color: #333; }
        p { margin: 0 0 10px; }
        ul { list-style: none; padding: 0; }
        li { margin-bottom: 5px; }
    </style>
</head>
<body>
    <h1>Order Confirmation</h1>
    <p>Thank you for your purchase!</p>
    <p><strong>Order ID:</strong> {{ $order->orderinfo_id }}</p>
    <p><strong>Date Placed:</strong> {{ $order->date_placed }}</p>
    <p><strong>Shipping Date:</strong> {{ $order->date_shipped }}</p>
    <p><strong>Shipping Cost:</strong> ${{ $order->shipping }}</p>
    <p><strong>Status:</strong> {{ $order->status }}</p>
    <h2>Order Details</h2>
    <ul>
        @foreach ($cart->items as $item)
            <li>
                Quantity: {{ $item['qty'] }} - {{ $item['item']->name }} - Price: ${{ $item['price'] }}
            </li>
        @endforeach
    </ul>
    <p><strong>Total Price:</strong> ${{ $cart->totalPrice + $order->shipping }}</p>
</body>
</html>
