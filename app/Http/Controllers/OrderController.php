<?php
// app/Http/Controllers/OrderController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function show($id)
    {
        $order = Order::with(['items.product'])->findOrFail($id);

        $orderDetails = $order->items->map(function ($item) {
            $product = $item->product;
            return [
                'sku' => $product->sku,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $item->quantity,
                'total' => $product->price * $item->quantity
            ];
        });

        return view('orders.show', compact('order', 'orderDetails'));
    }
}
