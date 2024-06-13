<!DOCTYPE html>
<html>
<head>
    <title>Commission Report</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>Commission Report</h1>
    <form method="GET" action="{{ route('commission.report') }}">
        <label for="distributor">Distributor:</label>
        <input type="text" id="distributor" name="distributor" value="{{ request('distributor') }}">
        <label for="order_date_from">Order Date From:</label>
        <input type="date" id="order_date_from" name="order_date_from" value="{{ request('order_date_from') }}">
        <label for="order_date_to">Order Date To:</label>
        <input type="date" id="order_date_to" name="order_date_to" value="{{ request('order_date_to') }}">
        <button type="submit">Filter</button>
    </form>
    <h3> <a href="{{ route('distributors.top') }}">View Top Distributors</a> </h3>

    @if ($orders->isEmpty())
        <p>No records found.</p>
    @else

    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Purchaser</th>
                <th>Distributor</th>
                <th>Referred Distributors</th>
                <th>Order Date</th>
                <th>Percentage</th>
                <th>Order Total</th>
                <th>Commission</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->invoice_number }}</td>
                    <td>{{ $order->purchaser ? $order->purchaser->id : 'N/A' }}</td>
                    <td>{{ $order->purchaser->referrer ? $order->purchaser->referrer->full_name : 'N/A' }}</td>
                    <td>{{ $order->purchaser->referrer ? $order->purchaser->referrer->referredDistributors->where('categories.name', 'Distributor')->count() : 0 }}</td>
                    <td>{{ $order->order_date }}</td>
                    <td>{{ $order->percentage }}%</td>
                    <td>${{ number_format($order->items->sum(function ($item) { return $item->product ? $item->product->price * $item->quantity : 0; }), 2) }}</td>
                    <td>${{ $order->formatted_commission }}</td>
                    <td><a href="{{ route('orders.show', $order->id) }}">View Details</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination">
        {{ $orders->appends(request()->except('page'))->links() }}
    </div>
    @endif
</body>
</html>
