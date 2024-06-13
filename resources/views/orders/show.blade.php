<!-- resources/views/orders/show.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Order Details</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>Order Details</h1>

    <h2>Invoice Number: {{ $order->invoice_number }}</h2>

    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orderDetails as $detail)
                <tr>
                    <td>{{ $detail['sku'] }}</td>
                    <td>{{ $detail['name'] }}</td>
                    <td>${{ number_format($detail['price'], 2) }}</td>
                    <td>{{ $detail['quantity'] }}</td>
                    <td>${{ number_format($detail['total'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
