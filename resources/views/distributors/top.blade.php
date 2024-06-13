<!DOCTYPE html>
<html>
<head>
    <title>Top Distributors Report</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>Top Distributors Report</h1>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Distributor</th>
                <th>Total Sales</th>
            </tr>
        </thead>
        <tbody>
            @php $rank = 1; @endphp
            @foreach ($distributors as $distributor)
                <tr>
                    <td>{{ $rank }}</td>
                    <td>{{ $distributor->first_name }} {{ $distributor->last_name }}</td>
                    <td>${{ number_format($distributor->total_sales, 2) }}</td>
                </tr>
                @php $rank++; @endphp
            @endforeach
        </tbody>
    </table>
</body>
</html>
