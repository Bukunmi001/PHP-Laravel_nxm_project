<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class DistributorService
{
    public function getTopDistributors($limit = 200)
{
    $distributors = DB::table('users')
        ->select('users.id', 'users.first_name', 'users.last_name', 'total_sales')
        ->join(
            DB::raw('(SELECT purchaser_id, SUM(order_items.quantity * products.price) AS total_sales 
                      FROM orders 
                      JOIN order_items ON orders.id = order_items.order_id 
                      JOIN products ON order_items.product_id = products.id 
                      GROUP BY purchaser_id) AS sales'),
            'users.id', '=', 'sales.purchaser_id'
        )
        ->orderBy('total_sales', 'desc')
        ->limit($limit)
        ->get();

    return $distributors;
}
}
